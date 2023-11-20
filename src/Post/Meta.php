<?php

namespace PostDraftPreview\Post;

class Meta
{
    public function getHash(int $postID): string
    {
        return get_post_meta($postID, 'pdp_hash', true);
    }

    private function setHash(string $hash, int $postID): void
    {
        if (@metadata_exists('post', $postID, 'pdp_hash')) {
            update_post_meta($postID, 'pdp_hash', $hash);
            return;
        }
        add_post_meta($postID, 'pdp_hash', $hash, true);
    }

    public function getStatus(int $postID): int
    {
        return get_post_meta($postID, 'pdp_status', true) ? 1 : 0;
    }

    public function setStatus(int $postID, int $status): void
    {
        if (1 === $status && ! @metadata_exists('post', $postID, 'pdp_hash')) {
            $this->setHash($this->generateRandomString() . $postID, $postID);
        }

        if (@metadata_exists('post', $postID, 'pdp_status')) {
            update_post_meta($postID, 'pdp_status', $status);
            return;
        }
        add_post_meta($postID, 'pdp_status', $status, true);
    }

    private function generateRandomString(int $length = 8): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $result;
    }

    public function getMetaCount(string $metaKey): int
    {
        if(! $metaKey) {
            return 0;
        }

        global $wpdb;

        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '" . $metaKey . "'");
    }

    public function removeAllData(): bool
    {
        global $wpdb;

        $metaCount = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = 'pdp_hash' OR meta_key = 'pdp_status' ");

        if(0 === $metaCount){
            return true;
        }

        if (! $wpdb->delete("{$wpdb->prefix}postmeta", ['meta_key' => 'pdp_hash'])) {
            return false;
        }

        if (! $wpdb->delete("{$wpdb->prefix}postmeta", ['meta_key' => 'pdp_status'])) {
            return false;
        }

        return true;
    }

    public function resetAllHashes(): bool
    {
        $results = $this->getAllPostmetaHashes();

        if (empty($results)) {
            return false;
        }

        array_map(function (array $result) {
            $this->setHash($this->generateRandomString() . $result['post_id'], $result['post_id']);
        }, $results);

        return true;
    }

    private function getAllPostmetaHashes(): array
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = %s";
        $params = ['pdp_hash'];
        $results = $wpdb->get_results($wpdb->prepare($query, $params));

        if (! empty($results)) {
            return array_map(function (object $item) {
                return (array) $item;
            }, $results);
        }
        return [];
    }

    public function setPostTypeAutogenerateSetting(string $postType, bool $enabled): bool
    {
        $optionName = "pdp_autogenerate_post_type_{$postType}";

        delete_option($optionName);
        return update_option($optionName, $enabled);
    }

    public function getPostTypeAutogenerateSetting(string $postType): bool
    {
        return get_option("pdp_autogenerate_post_type_{$postType}", false);
    }
}

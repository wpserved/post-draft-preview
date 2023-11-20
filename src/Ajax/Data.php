<?php

namespace PostDraftPreview\Ajax;

class Data
{
    /**
     * @action admin_enqueue_scripts
     */
    public function adminDependencies(): void
    {
        wp_localize_script('pdp/admin.js', 'pdp', $this->getJsAdminConfig());
    }

    private function getJsAdminConfig(): array
    {
        global $pagenow;

        $data = [];
        $data['ajaxurl'] = apply_filters('pdp/ajaxurl', admin_url('admin-ajax.php'));

        if (
            'tools.php' === $pagenow
            && isset($_GET['page'])
            && 'pdp-dashboard' === $_GET['page']
        ) {
            $data['dashboard']['data']['resetNonce'] = apply_filters('pdp/dashboard/data/reset/nonce', '');
            $data['dashboard']['data']['removeNonce'] = apply_filters('pdp/dashboard/data/remove/nonce', '');
            $data['dashboard']['data']['autogenerateNonce'] = apply_filters('pdp/dashboard/data/autogenerate/nonce', '');

            return $data;
        }

        if ('post.php' !== $pagenow) {
            return $data;
        }

        if (empty($_GET['post'])) {
            return $data;
        }

        $postStatusObj = get_post_status_object(get_post_status($_GET['post']));
        if ('draft' !== $postStatusObj->name) {
            return $data;
        }

        $data['post'] = [
            'changeStatusNonce' => apply_filters('pdp/post/changestatus/nonce', ''),
            'getStatusNonce' => apply_filters('pdp/post/getstatus/nonce', ''),
            'previewUrl' => apply_filters('pdp/post/previewurl', '#'),
            'id' => apply_filters('pdp/post/id', '#'),
            'status' => apply_filters('pdp/post/status', 0),
            'previewLabel' => apply_filters('pdp/post/previewlabel', '')
        ];

        return $data;
    }
}

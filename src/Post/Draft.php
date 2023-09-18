<?php

namespace PostDraftPreview\Post;

use PostDraftPreview\Post\Post;
use WP_Post;

class Draft
{
    private ?Post $post = null;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @filter posts_results
     */
    public function enableDraftPreview(array $posts, \WP_Query $query): ?array
    {
        if (1 !== sizeof($posts)) {
            return $posts;
        }

        $postStatusObj = get_post_status_object(get_post_status($posts[0]));
        $passedCheck = false;

        if ('draft' !== $postStatusObj->name) {
            return $posts;
        }

        if (
            ! empty($_GET['hash'])
            && 1 === $this->post->getMeta()->getStatus($posts[0]->ID)
            && $_GET['hash'] === $this->post->getMeta()->getHash($posts[0]->ID)
        ) {
            $passedCheck = true;
        }

        if (true !== $passedCheck) {
            return $posts;
        }

        $query->_draft_post = $posts;

        add_filter('the_posts', [$this, 'displayDraftPost'], 10, 2);

        return $query->_draft_post;
    }

    public function displayDraftPost(?array $posts, \WP_Query $query): ?array
    {
        remove_filter('the_posts', 'display_draft_post', 10, 2);
        return $query->_draft_post;
    }

    public function getPublicDraftPreviewUrl(\WP_Post $post): string
    {
        return ! empty($this->post->getMeta()->getHash($post->ID)) ? add_query_arg('hash', $this->post->getMeta()->getHash($post->ID), get_permalink($post)) : '';
    }

    /**
     * @_action save_post
     */
    public function onSavePost(int $postId, \WP_Post $post, bool $update): void
    {
        if (! isset($_POST['pdp_auto_generate'])) {
            return;
        }

        update_option('pdp_auto_generate', $_POST['pdp_auto_generate']);
    }
}

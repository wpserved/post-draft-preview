<?php

namespace PostDraftPreview\Post;

use PostDraftPreview\Post\Post;

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
        if (! empty($posts)) {
            return $posts;
        }

        remove_filter('the_posts', 'display_draft_post', 10, 2);
        return $query->_draft_post;
    }

    public function getPublicDraftPreviewUrl(\WP_Post $post): string
    {
        $queryArgs = [
            'hash' => $this->post->getMeta()->getHash($post->ID),
            'preview' => 'true',
        ];

        $previewUrl = add_query_arg($queryArgs, get_permalink($post));

        return ! empty($this->post->getMeta()->getHash($post->ID)) ? $previewUrl : '';
    }
}

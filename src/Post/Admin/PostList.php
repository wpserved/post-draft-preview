<?php

namespace PostDraftPreview\Post\Admin;

use PostDraftPreview\Post\Post;

class PostList
{
    private ?Post $post = null;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @filter post_row_actions 10 2
     */
    public function postRowActionsLink(array $actions, \WP_Post $post): array
    {
        if (! $this->post->getMeta()->getStatus($post->ID)) {
            return $actions;
        }

        if (in_array(get_post_status($post), array('draft', 'pending', 'auto-draft'))) {
            $actions[] = '<a href="' . $this->post->getDraft()->getPublicDraftPreviewUrl($post) . '" target="_blank">' . __('Public draft preview', 'pdp') . '</a>';
        }

        return $actions;
    }
}

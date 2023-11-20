<?php

namespace PostDraftPreview\Dashboard\Data\Autogenerate;

use PostDraftPreview\Post\Meta;
use WP_Post;

class Save
{
    /**
     * @action save_post
     */
    public function enablePostDraftPreview(int $postId, WP_Post $post, bool $postUpdated): void
    {
        if ('draft' !== $post->post_status) {
            return;
        }

        $meta = new Meta();

        $shouldAutogeneratePostDraftPreview = $meta->getPostTypeAutogenerateSetting($post->post_type);

        if (false === $shouldAutogeneratePostDraftPreview) {
            return;
        }

        $meta->setStatus($post->ID, $meta->getStatus($post->ID) ? 0 : 1);
    }
}

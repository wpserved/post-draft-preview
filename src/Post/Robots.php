<?php

namespace PostDraftPreview\Post;

class Robots
{
    private ?Post $post = null;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @filter wp_robots
     */
    public function blockRobots(array $robots): array
    {
        if (! is_singular()) {
            return $robots;
        }

        $post = get_post();

        if ('draft' !== $post->post_status) {
            return $robots;
        }

        if (1 !== $this->post->getMeta()->getStatus($post->ID)) {
            return $robots;
        }

        $robots['noindex'] = true;
        $robots['nofollow'] = true;

        return $robots;
    }
}

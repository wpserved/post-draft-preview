<?php

namespace PostDraftPreview\Post\Admin;

use PostDraftPreview\Post\Post;
class PostEdit
{
    private ?Post $post = null;

    public function __construct(Post $post)
    {
        $this->post = $post;
        createClass('PostDraftPreview\Post\Admin\PostEdit\AjaxChangeStatus', [$this]);
        createClass('PostDraftPreview\Post\Admin\PostEdit\AjaxGetStatus', [$this]);
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}

<?php

namespace PostDraftPreview\Post;

use PostDraftPreview\Post\Admin\PostList;
use PostDraftPreview\Post\Admin\PostEdit;
use PostDraftPreview\Post\Meta;
use PostDraftPreview\Post\Draft;

class Post
{
    private ?PostList $PostList = null;
    private ?PostEdit $PostEdit = null;
    private ?Meta $meta = null;
    private ?Draft $draft = null;
    private ?Robots $robots = null;

    public function __construct()
    {
        $this->PostList = createClass(PostList::class, [$this]);
        $this->PostEdit = createClass(PostEdit::class, [$this]);
        $this->meta = createClass(Meta::class);
        $this->draft = createClass(Draft::class, [$this]);
        $this->robots = createClass(Robots::class, [$this]);
    }

    public function getMeta(): Meta
    {
        return $this->meta;
    }

    public function getDraft(): Draft
    {
        return $this->draft;
    }
}

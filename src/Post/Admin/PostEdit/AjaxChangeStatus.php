<?php

namespace PostDraftPreview\Post\Admin\PostEdit;

use PostDraftPreview\Ajax\AbstractAjax;
use PostDraftPreview\Post\Admin\PostEdit;
use PostDraftPreview\Post\Meta;

class AjaxChangeStatus extends AbstractAjax
{
    private string $endpoint = 'pdp-change-status';
    private ?PostEdit $postEdit = null;

    public function __construct(PostEdit $postEdit)
    {
        parent::__construct($this->endpoint);
        $this->postEdit = $postEdit;
    }

    public function callback()
    {
        $this->validate();

        if (! isset($_POST['id']) || ! $_POST['id']) {
            wp_send_json_error(['message' => __('Incomplete data.', 'pdp')]);
        }

        if (! is_numeric($_POST['id'])) {
            wp_send_json_error(['message' => __('Invalid post ID.', 'pdp')]);
        }

        if (! $post = get_post($_POST['id'])) {
            wp_send_json_error(['message' => __('Post doesn\'t exist.', 'pdp')]);
        }

        $meta = new Meta();

        $meta->setStatus($post->ID, $meta->getStatus($post->ID) ? 0 : 1);

        $responseData = [
            'id' => $post->ID,
            'status' => $meta->getStatus($post->ID),
            'previewUrl' => $this->postEdit->getPost()->getDraft()->getPublicDraftPreviewUrl($post)
        ];

        wp_send_json_success($responseData);
    }

    /**
     * @filter pdp/post/changestatus/nonce
     */
    public function generateNonce(): string
    {
        return wp_create_nonce($this->endpoint);
    }

    /**
     * @filter pdp/post/previewurl
     */
    public function setPreviewUrl(): string
    {
        return is_admin() && ! empty($post = get_post()) ? $this->postEdit->getPost()->getDraft()->getPublicDraftPreviewUrl($post) : '';
    }

    /**
     * @filter pdp/post/id
     */
    public function getPostID(): string
    {
        return get_post()->ID;
    }

    /**
     * @filter pdp/post/status
     */
    public function setPreviewStatus(): int
    {
        return is_admin() && ! empty($post = get_post()) ? $this->postEdit->getPost()->getMeta()->getStatus($post->ID) : 0;
    }

    /**
     * @filter pdp/post/previewlabel
     */
    public function setPreviewLabel(): string
    {
        return __('Public preview url', 'pdp');
    }
}

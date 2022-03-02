<?php

namespace PostDraftPreview\Post\Admin\PostEdit;

use PostDraftPreview\Ajax\AbstractAjax;
use PostDraftPreview\Post\Admin\PostEdit;
use PostDraftPreview\Post\Meta;

class AjaxGetStatus extends AbstractAjax
{
    private string $endpoint = 'pdp-get-status';
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

        $responseData = [
            'id' => $post->ID,
            'status' => $meta->getStatus($post->ID),
            'previewUrl' => $this->postEdit->getPost()->getDraft()->getPublicDraftPreviewUrl($post)
        ];

        wp_send_json_success($responseData);
    }

    /**
     * @filter pdp/post/getstatus/nonce
     */
    public function generateNonce(): string
    {
        return wp_create_nonce($this->endpoint);
    }
}

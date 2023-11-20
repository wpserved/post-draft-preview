<?php

namespace PostDraftPreview\Dashboard\Data\Autogenerate;

use PostDraftPreview\Ajax\AbstractAjax;
use PostDraftPreview\Post\Meta;

class Ajax extends AbstractAjax
{
    private string $endpoint = 'pdp-data-autogenerate';

    public function __construct()
    {
        parent::__construct($this->endpoint);
    }

    public function callback()
    {
        $this->validate();

        $responseData = [];

        $meta = new Meta();

        $this->saveAutogenerationSettings($meta);

        $responseData['message'] = __('Autogeneration settings saved successfully.', 'pdp');

        wp_send_json_success($responseData);
    }

    /**
     * @filter pdp/dashboard/data/autogenerate/nonce
     */
    public function generateNonce(): string
    {
        return wp_create_nonce($this->endpoint);
    }

    private function saveAutogenerationSettings(Meta $meta): void
    {
        if (! isset($_POST['post_types']) || empty($_POST['post_types'])) {
            return;
        }

        foreach ($_POST['post_types'] as $postType => $value) {
            $meta->setPostTypeAutogenerateSetting($postType, 'on' === $value);
        }
    }
}

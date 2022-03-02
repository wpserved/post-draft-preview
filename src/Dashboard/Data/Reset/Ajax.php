<?php

namespace PostDraftPreview\Dashboard\Data\Reset;

use PostDraftPreview\Ajax\AbstractAjax;
use PostDraftPreview\Post\Meta;

class Ajax extends AbstractAjax
{
    private string $endpoint = 'pdp-data-reset';

    public function __construct()
    {
        parent::__construct($this->endpoint);
    }

    public function callback()
    {
        $this->validate();

        $responseData = [];

        $meta = new Meta();

        if ($meta->getMetaCount('pdp_hash') < 1) {
            $responseData['message'] = __('Reset cannot be performed cause no hash generated yet.', 'pdp');
            wp_send_json_error($responseData);
        }

        if ($meta->resetAllHashes()) {
            $responseData['message'] = __('Reset was successful.', 'pdp');
            wp_send_json_success($responseData);
        } else {
            $responseData['message'] = __('Reset failed.', 'pdp');
            wp_send_json_error($responseData);
        }
    }

    /**
     * @filter pdp/dashboard/data/reset/nonce
     */
    public function generateNonce(): string
    {
        return wp_create_nonce($this->endpoint);
    }
}

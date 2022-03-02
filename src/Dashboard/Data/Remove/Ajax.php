<?php

namespace PostDraftPreview\Dashboard\Data\Remove;

use PostDraftPreview\Ajax\AbstractAjax;
use PostDraftPreview\Post\Meta;

class Ajax extends AbstractAjax
{
    private string $endpoint = 'pdp-data-remove';

    public function __construct()
    {
        parent::__construct($this->endpoint);
    }

    public function callback()
    {
        $this->validate();

        $responseData = [];

        $meta = new Meta();

        if ($meta->removeAllData()) {
            $responseData['message'] = __('All data has been removed.', 'pdp');
            wp_send_json_success($responseData);
        } else {
            $responseData['message'] = __('An error occured.', 'pdp');
            wp_send_json_error($responseData);
        }
    }

    /**
     * @filter pdp/dashboard/data/remove/nonce
     */
    public function generateNonce(): string
    {
        return wp_create_nonce($this->endpoint);
    }
}

<?php

namespace PostDraftPreview\Dashboard\Data\Reset;

class Reset
{
    public function __construct()
    {
        createClass('PostDraftPreview\Dashboard\Data\Reset\Ajax');
    }

    public function view(): void
    {
        include PDP_RESOURCES_PATH . '/views/admin/settings/data-reset.php';
    }
}

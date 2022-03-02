<?php

namespace PostDraftPreview\Dashboard\Data\Remove;

class Remove
{
    public function __construct()
    {
        createClass('PostDraftPreview\Dashboard\Data\Remove\Ajax');
    }

    public function view(): void
    {
        include PDP_RESOURCES_PATH . '/views/admin/settings/data-remove.php';
    }
}

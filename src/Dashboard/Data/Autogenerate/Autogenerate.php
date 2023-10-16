<?php

namespace PostDraftPreview\Dashboard\Data\Autogenerate;

use PostDraftPreview\Post\Meta;
use WP_Post_Type;

class Autogenerate
{
    public function __construct()
    {
        createClass('PostDraftPreview\Dashboard\Data\Autogenerate\Ajax');
        createClass('PostDraftPreview\Dashboard\Data\Autogenerate\Save');
    }

    public function view(): void
    {
        $postTypes = get_post_types([
            'public' => true,
            'show_ui' => true,
        ], 'objects');

        $meta = new Meta();

        $postTypes = array_map(fn (WP_Post_Type $postType): array => [
            'name' => $postType->name,
            'label' => $postType->label,
            'value' => $meta->getPostTypeAutogenerateSetting($postType->name),
        ], $postTypes);

        include PDP_RESOURCES_PATH . '/views/admin/settings/data-autogenerate.php';
    }
}

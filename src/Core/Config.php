<?php

namespace PostDraftPreview\Core;

class Config
{
    /**
     * @action plugins_loaded
     */
    public function initConfig(): void
    {
        load_textdomain('pdp', PDP_RESOURCES_PATH . '/lang/' . get_locale() . '.mo');
    }

    /**
     * @action wp_enqueue_scripts
     */
    public function dependencies(): void
    {
        $version = 'production' === wp_get_environment_type() ? null : time();

        wp_enqueue_style('pdp/front.css', PDP_ASSETS_URI . '/styles/front.css', false, $version);
        wp_enqueue_script('pdp/manifest.js', PDP_ASSETS_URI . '/scripts/manifest.js', ['jquery'], $version, true);
        wp_enqueue_script('pdp/front.js', PDP_ASSETS_URI . '/scripts/front.js', ['pdp/manifest.js'], $version, true);
    }

    /**
     * @action admin_enqueue_scripts
     */
    public function adminDependencies(): void
    {
        $version = 'production' === wp_get_environment_type() ? null : time();

        wp_enqueue_style('pdp/admin.css', PDP_ASSETS_URI . '/styles/admin.css', false, $version);
        wp_enqueue_script('pdp/manifest.js', PDP_ASSETS_URI . '/scripts/manifest.js', ['jquery'], $version, true);
        wp_enqueue_script('pdp/admin.js', PDP_ASSETS_URI . '/scripts/admin.js', ['pdp/manifest.js'], $version, true);
    }
}

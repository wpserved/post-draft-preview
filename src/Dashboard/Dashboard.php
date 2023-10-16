<?php

namespace PostDraftPreview\Dashboard;

use PostDraftPreview\Dashboard\Data\Autogenerate\Autogenerate;
use PostDraftPreview\Dashboard\Data\Reset\Reset;
use PostDraftPreview\Dashboard\Data\Remove\Remove;

class Dashboard
{
    private ?Reset $dataReset = null;
    private ?Remove $dataRemove = null;
    private ?Autogenerate $autogenerate = null;

    public function __construct()
    {
        $this->dataReset = createClass(Reset::class);
        $this->dataRemove = createClass(Remove::class);
        $this->autogenerate = createClass(Autogenerate::class);
    }

    /**
     * @action admin_menu
     */
    public function setupMenu(): void
    {
        add_submenu_page('tools.php', __('Post Draft Preview', 'pdp'), __('Post Draft Preview', 'pdp'), 'manage_options', 'pdp-dashboard', [$this, 'dashboardInit']);
    }

    public function dashboardInit(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }
        include PDP_RESOURCES_PATH . '/views/admin/dashboard.php';
    }
}

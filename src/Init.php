<?php

namespace PostDraftPreview;

use PostDraftPreview\Core\Singleton;

class Init extends Singleton
{
    public array $public = [];

    public array $private = [];

    public function __construct()
    {
        $this->addPrivate('Core\Config');
        $this->addPrivate('Ajax\Ajax');
        $this->addPrivate('Dashboard\Dashboard');
        $this->addPrivate('Post\Post');
    }

    private function addPublic(string $name, string $label = ''): void
    {
        $class = 'PostDraftPreview\\' . $name;
        $index = ! empty($label) ? $label : $name;
        $this->public[$index] = new $class();
        pdpDoc()->addDocHooks($this->public[$index]);
    }

    private function addPrivate(string $name, string $label = ''): void
    {
        $class = 'PostDraftPreview\\' . $name;
        $index = ! empty($label) ? $label : $name;
        $this->private[$index] = new $class();
        pdpDoc()->addDocHooks($this->private[$index]);
    }
}

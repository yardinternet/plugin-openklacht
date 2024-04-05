<?php

namespace OWC\OpenKlacht\Foundation;

use OWC\OpenKlacht\Settings\SettingsPageOptions;

abstract class ServiceProvider
{
    public Config $config;
    public SettingsPageOptions $settings;

    public const NAME = 'openklacht';

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->config = new Config(OWC_OPENKLACHT_ROOT_PATH . '/config');
        $this->config->boot();

        $this->settings = SettingsPageOptions::make();

        \load_plugin_textdomain(self::NAME, false, self::NAME . '/languages/');
    }

    abstract public function register(): void;
}

<?php

namespace OWC\OpenKlacht\Settings;

class SettingsPageOptions
{
    /**
     * Settings defined on settings page
     */
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getPortalURL(): string
    {
        return $this->settings['okl_setting_openklacht_portal_url'] ?? '';
    }

    public function getPortalItemSlug(): string
    {
        return $this->settings['okl_setting_openklacht_item_slug'] ?? '';
    }

    public function isPortalSlugValid(): bool
    {
        return ! empty($this->getPortalURL()) && ! empty($this->getPortalItemSlug());
    }

    public function usePortalURL(): bool
    {
        $setting = $this->settings['okl_setting_openklacht_use_portal_url'] ?? false;

        return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
    }

    public static function make(): self
    {
        $defaultSettings = [
            'okl_setting_openklacht_portal_url' => '',
            'okl_setting_openklacht_item_slug' => '',
            'okl_setting_openklacht_use_portal_url' => 'on',
        ];

        return new static(wp_parse_args(get_option('_owc_openklacht_base_settings'), $defaultSettings));
    }
}

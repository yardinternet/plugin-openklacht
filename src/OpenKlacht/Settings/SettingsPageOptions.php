<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Settings;

/**
 * @phpstan-consistent-constructor
 */
class SettingsPageOptions
{
	/**
	 * @param array<string, mixed> $settings Settings defined on the settings page.
	 */
	public function __construct(private readonly array $settings)
	{
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

	public static function make(): static
	{
		$defaultSettings = [
			'okl_setting_openklacht_portal_url' => '',
			'okl_setting_openklacht_item_slug' => '',
			'okl_setting_openklacht_use_portal_url' => 'on',
		];

		return new static(wp_parse_args(get_option('_owc_openklacht_base_settings'), $defaultSettings));
	}
}

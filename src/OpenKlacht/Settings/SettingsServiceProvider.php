<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Settings;

use OWC\OpenKlacht\Foundation\Meta;
use OWC\OpenKlacht\Foundation\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		add_action('cmb2_admin_init', $this->registerSettingsPages(...), 10, 0);
	}

	public function registerSettingsPages(): void
	{
		$settingsPages = $this->config->get('settings_pages');

		if (! is_array($settingsPages)) {
			return;
		}

		foreach ($settingsPages as $page) {
			if (! is_array($page)) {
				continue;
			}

			$this->registerSettingsPage($page);
		}
	}

	protected function registerSettingsPage(array $page): void
	{
		$fields = $page['fields'] ?? [];
		unset($page['fields']); // Fields will be added later on.

		$optionsPage = \new_cmb2_box($page);

		if (empty($fields) || ! is_array($fields)) {
			return;
		}

		Meta::addFields($optionsPage, $fields);
	}
}

<?php

declare(strict_types=1);

namespace OWC\OpenKlacht;

use OWC\OpenKlacht\Foundation\ServiceProvider;

class OpenKlachtServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->bootProviders();
	}

	protected function bootProviders(): void
	{
		$providers = $this->config->get('core.providers');

		if (! is_array($providers) || empty($providers)) {
			return;
		}

		foreach ($providers as $provider) {
			if (! class_exists($provider)) {
				continue;
			}

			(new $provider())->register();
		}
	}
}

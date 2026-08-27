<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\ElasticSearch;

use OWC\OpenKlacht\Foundation\ServiceProvider;

class ElasticSearchServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->hooks();
	}

	public function hooks(): void
	{
		add_filter('ep_post_sync_args', (new Hooks())->extendSyncArgs(...), 20, 2);
	}
}

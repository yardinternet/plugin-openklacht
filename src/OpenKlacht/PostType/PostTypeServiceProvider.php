<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\PostType;

use OWC\OpenKlacht\Foundation\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		add_action('init', $this->registerPostTypes(...));
		add_action('cmb2_admin_init', $this->registerMetaFields(...));
	}

	public function registerPostTypes(): void
	{
		register_post_type('openklacht', [
			'label' => 'OpenKlacht',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'query_var' => false,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'supports' => ['title', 'author'],
		]);
	}

	public function registerMetaFields(): void
	{
		(new MetaboxFields($this->config->get('metaboxes')))->createMetaFields();
	}
}

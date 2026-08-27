<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\RestAPI;

use OWC\OpenKlacht\Foundation\Meta;
use OWC\OpenKlacht\Foundation\ServiceProvider;
use WP_Post;

class RestAPIServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->hooks();

		add_action('rest_api_init', function (): void {
			$this->registerPortalURLField();
			$this->registerExtraRestFields();
		});
	}

	public function hooks(): void
	{
		$hooks = new Hooks();

		add_filter('query_vars', $hooks->addSlugQueryVar(...), 10, 1);
		add_filter('rest_post_query', $hooks->extendRestQuery(...), 10, 2);
	}

	public function registerPortalURLField(): void
	{
		if (! $this->settings->usePortalURL()) {
			return;
		}

		register_rest_field(['openklacht'], 'portal_url', [
			'get_callback' => function ($object) {
				$post = get_post($object['id']);

				if (! $post instanceof WP_Post) {
					return null;
				}

				return sprintf('%s/%s/%s', untrailingslashit($this->settings->getPortalURL()), untrailingslashit($this->settings->getPortalItemSlug()), $post->post_name);
			},
		]);
	}

	protected function registerExtraRestFields(): void
	{
		$fields = $this->config->get('metaboxes');

		if (! is_array($fields) || empty($fields)) {
			return;
		}

		// Share one callback for all fields to avoid creating a new closure for each field.
		$getValue = $this->getFieldValue(...);

		foreach ($fields as $field) {
			if (empty($field['id'])) {
				continue;
			}

			register_rest_field(['openklacht'], Meta::key($field['id']), [
				'get_callback' => $getValue,
			]);
		}
	}

	/**
	 * @param array<string, mixed> $object   REST response of the post being prepared.
	 * @param string               $metaKey  Name of the REST field being resolved.
	 */
	public function getFieldValue(array $object, string $metaKey): mixed
	{
		return get_post_meta($object['id'], $metaKey, true);
	}
}

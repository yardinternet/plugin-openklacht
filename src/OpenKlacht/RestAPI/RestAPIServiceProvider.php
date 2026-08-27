<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\RestAPI;

use OWC\OpenKlacht\Foundation\ServiceProvider;
use WP_Post;

class RestAPIServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->hooks();
		$this->registerPortalURLField();
		$this->registerExtraRestFields();
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

		foreach ($fields as $field) {
			if (empty($field['id'])) {
				continue;
			}

			$this->registerField($field);
		}
	}

	protected function registerField(array $field): void
	{
		register_rest_field(['openklacht'], sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']), [
			'get_callback' => fn ($object) => $this->getFieldValue($object, $field),
		]);
	}

	/**
	 * @param array<string, mixed> $object REST response of the post being prepared.
	 * @param array<string, mixed> $field  Field definition from the metaboxes config.
	 */
	public function getFieldValue(array $object, array $field): mixed
	{
		$metaKey = sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']);

		return get_post_meta($object['id'], $metaKey, true);
	}
}

<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\RestAPI;

use WP_REST_Request;

class Hooks
{
	public function addSlugQueryVar(array $vars): array
	{
		$vars[] = 'slug';

		return $vars;
	}

	public function extendRestQuery(array $args, WP_REST_Request $request): array
	{
		$slug = $request->get_param('slug');

		if (! empty($slug)) {
			$args['name'] = $slug; // 'name' is the WP query parameter for slug.
		}

		return $args;
	}
}

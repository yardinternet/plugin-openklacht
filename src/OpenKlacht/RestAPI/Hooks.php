<?php

namespace OWC\OpenKlacht\RestAPI;

class Hooks
{
    public function addSlugQueryVar(array $vars): array
    {
        $vars[] = 'slug';

        return $vars;
    }

    public function extendRestQuery($args, $request): array
    {
        $slug = $request->get_param('slug');

        if (! empty($slug)) {
            $args['name'] = $slug; // 'name' is the WP query parameter for slug.
        }

        return $args;
    }
}

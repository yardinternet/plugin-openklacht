<?php

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
        $hooks = new Hooks;

        add_filter('query_vars', [$hooks, 'addSlugQueryVar'], 10, 1);
        add_filter('rest_post_query', [$hooks, 'extendRestQuery'], 10, 2);
    }

    public function registerPortalURLField()
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

    protected function registerExtraRestFields()
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

    protected function registerField(array $field)
    {
        register_rest_field(['openklacht'], sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']), [
            'get_callback' => function ($object) use ($field) {
                return $this->getFieldValue($object, $field);
            },
        ]);
    }

    public function getFieldValue($object, $field)
    {
        $postID = $object['id'];
        $metaKey = sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']);

        return get_post_meta($postID, $metaKey, true);
    }
}

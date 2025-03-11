<?php

namespace OWC\OpenKlacht\Admin;

use OWC\OpenKlacht\Foundation\ServiceProvider;
use WP_Post;
use WP_REST_Response;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_filter('post_type_link', [$this, 'filterPostLink'], 10, 4);
        add_filter('preview_post_link', [$this, 'filterPreviewLink'], 10, 2);
        add_filter('rest_prepare_openklacht', [$this, 'filterPreviewInNewTabLink'], 10, 2);
    }

    /**
     * Change the url for preview of published posts in the portal.
     */
    public function filterPostLink(string $link, WP_Post $post, bool $leavename, $sample): string
    {
        if ('openklacht' !== $post->post_type || ! $this->settings->isPortalSlugValid()) {
            return $link;
        }

        return sprintf('%s/%s/%s/', untrailingslashit($this->settings->getPortalURL()), untrailingslashit($this->settings->getPortalItemSlug()), ($leavename ? '%postname%' : $post->post_name));
    }

    /**
     * Change the url for preview of draft posts in the portal.
     */
    public function filterPreviewLink(string $link, WP_Post $post): string
    {
        if ('openklacht' !== $post->post_type || ! $this->settings->isPortalSlugValid()) {
            return $link;
        }

        return sprintf('%s/%s?draft-preview=true', untrailingslashit($this->settings->getPortalURL()), untrailingslashit($this->settings->getPortalItemSlug()));
    }

    /**
     * Change the url of "preview in new tab" button for preview in the portal.
     */
    public function filterPreviewInNewTabLink(WP_REST_Response $response, WP_Post $post): WP_REST_Response
    {
        if ('publish' === $post->post_status || ! $this->settings->isPortalSlugValid()) {
            return $response;
        }

        $response->data['link'] = sprintf('%s/%s?draft-preview=true', untrailingslashit($this->settings->getPortalURL()), untrailingslashit($this->settings->getPortalItemSlug()));

        return $response;
    }
}

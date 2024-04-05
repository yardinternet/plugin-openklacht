<?php

declare(strict_types=1);

namespace Yard\OpenKlacht;

class OpenKlachtServiceProvider
{
    const POST_TYPE = 'openklacht';

    public function boot(): void
    {
        add_action('init', [$this, 'registerPostTypes']);
        add_action('cmb2_admin_init', [$this, 'registerMetaFields']);
        add_action('gform_after_submission', [$this, 'handleFormSubmission'], 10, 2);
    }

    public function registerPostTypes(): void
    {
        register_post_type(self::POST_TYPE, [
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
            'menu_position' => null,
            'supports' => ['title', 'author', 'excerpt'],
        ]);
    }

    public function registerMetaFields(): void
    {
        $metaFields = new OpenKlachtFields();
        $metaFields->createMetaFields();
    }

    public function handleFormSubmission($entry, $form): void
    {
        OpenKlachtSubmissionHandler::make($entry, $form)->handle();
    }
}

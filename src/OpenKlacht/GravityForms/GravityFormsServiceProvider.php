<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

use OWC\OpenKlacht\Foundation\ServiceProvider;

class GravityFormsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action('gform_after_submission', [$this, 'handleFormSubmission'], 10, 2);
    }

    public function handleFormSubmission($entry, $form): void
    {
        SubmissionHandler::make($entry, $form)->handle();
    }
}

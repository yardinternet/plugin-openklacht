<?php

declare(strict_types=1);

namespace Yard\OpenKlacht;

class OpenKlachtInformationAfterSubmit extends OpenKlachtAbstractAfterSubmit
{
    public function handle(): void
    {
        $postID = wp_insert_post($this->getArgs(), true);

        if (! $postID) {
            return;
        }
    }

    protected function getArgs(): array
    {
        return [
            'post_title' => sanitize_text_field($this->values['title']),
            'post_content' => '',
            'post_status' => 'draft',
            'post_author' => 1,
            'post_type' => 'openklacht',
            'meta_input' => $this->getMetaArgs(),
        ];
    }

    protected function getMetaArgs(): array
    {
        $fields = [
            'reference',
            'title',
            'date_received',
            'description',
            'organization',
            'function',
            'findings',
            'judgement',
            'conclusion',
            'judgement_date',
            'file_number',
        ];

        return collect($fields)->mapWithKeys(function ($field) {
            return ['okl_' . $field => $this->values[$field] ?? ''];
        })->filter()->toArray();
    }
}

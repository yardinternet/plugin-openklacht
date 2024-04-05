<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

class AfterSubmit extends AbstractAfterSubmit
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
            'year_received',
            'description',
            'organization',
            'function',
            'findings',
            'judgement',
            'conclusion',
            'judgement_date',
            'judgement_year',
        ];

        $metaArgs = collect($fields)->mapWithKeys(function ($field) {
            if (('date_received' === $field || 'judgement_date' === $field) && isset($this->values[$field])) {
                $date = new \DateTime($this->values[$field]);
                $year = $date->format('Y'); // Extract the year

                if ('date_received' === $field) {
                    $this->values['year_received'] = $year;
                } elseif ('judgement_date' === $field) {
                    $this->values['judgement_year'] = $year;
                }

                $formatter = new \IntlDateFormatter('nl_NL', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
                $formattedDate = $formatter->format($date);

                return ['okl_' . $field => $formattedDate];
            }

            return ['okl_' . $field => $this->values[$field] ?? ''];
        })->filter()->toArray();

        $metaArgs['okl_year_received'] = $this->values['year_received'] ?? '';
        $metaArgs['okl_judgement_year'] = $this->values['judgement_year'] ?? '';

        return $metaArgs;
    }
}

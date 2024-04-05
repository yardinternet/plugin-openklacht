<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

use GF_Field;

class SubmissionHandler
{
    private array $entry;
    private array $form;
    private const FORMS = [
        'Formulier OpenKlacht' => AfterSubmit::class,
    ];

    public function __construct($entry, $form)
    {
        $this->entry = $entry;
        $this->form = $form;
    }

    public static function make($entry, $form): self
    {
        return new static($entry, $form);
    }

    public function handle(): array
    {
        if (empty($form = $this->determineForm())) {
            return $this->entry;
        }

        $enteredValues = $this->getEnteredValues();
        $form::make($this->form, $enteredValues, $this->entry)->handle();

        return $this->entry;
    }

    private function determineForm(): string
    {
        return self::FORMS[$this->form['title']] ?? '';
    }

    private function getEnteredValues(): array
    {
        $values = $this->mapValues();

        return array_filter($values);
    }

    private function mapValues(): array
    {
        $mapping = [];

        foreach ($this->form['fields'] as $field) {
            if (empty($field['id']) || empty($field['label'])) {
                continue;
            }

            if (! empty($field->inputs)) {
                $mapping = $this->mapValuesFromInputs($mapping, $field);
            } else {
                $mapping[$field['adminLabel'] ? sanitize_title($field['adminLabel']) : sanitize_title($field['label'])] = rgar($this->entry, $field['id']);
            }
        }

        return $mapping;
    }

    private function mapValuesFromInputs(array $mapping, GF_Field $field): array
    {
        foreach ($field->inputs as $input) {
            if (empty($input['id'])) {
                continue;
            }

            $mapping[! empty($field['adminLabel']) ? sanitize_title($field['adminLabel']) : sanitize_title($field['label'])][] = rgar($this->entry, $input['id']);
        }

        return $mapping;
    }
}

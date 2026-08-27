<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

use GF_Field;

/**
 * @phpstan-consistent-constructor
 */
class SubmissionHandler
{
	private const FORMS = [
		'Formulier OpenKlacht' => AfterSubmit::class,
	];

	public function __construct(
		private readonly array $entry,
		private readonly array $form
	) {
	}

	public static function make(array $entry, array $form): static
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
				$mapping[$this->fieldKey($field)] = rgar($this->entry, $field['id']);
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

			$mapping[$this->fieldKey($field)][] = rgar($this->entry, $input['id']);
		}

		return $mapping;
	}

	/**
	 * The admin label wins when set, so form labels can be reworded without breaking
	 * the mapping the AfterSubmit handlers read.
	 */
	private function fieldKey(GF_Field $field): string
	{
		return ! empty($field['adminLabel'])
			? sanitize_title($field['adminLabel'])
			: sanitize_title($field['label']);
	}
}

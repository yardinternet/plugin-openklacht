<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

use DateTimeImmutable;
use Exception;
use OWC\OpenKlacht\Foundation\Meta;

class AfterSubmit extends AbstractAfterSubmit
{
	public function handle(): void
	{
		wp_insert_post($this->getArgs());
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
		];

		$metaArgs = [];
		foreach ($fields as $field) {
			if (isset(Meta::DATE_FIELDS[$field])) {
				// Yields the date in both representations plus the derived year.
				$metaArgs += $this->dateMetaArgs($field);
			} elseif ('function' === $field) {
				$metaArgs[Meta::key($field)] = $this->handleFunctionField();
			} else {
				$metaArgs[Meta::key($field)] = $this->values[$field] ?? '';
			}
		}

		$metaArgs = array_filter($metaArgs);

		// Unioned after array_filter so the year keys persist even when empty: the
		// Elasticsearch year facet relies on them being present. A year derived from
		// a parsed date is already in $metaArgs and takes precedence over a
		// form-supplied one.
		return $metaArgs + [
			Meta::key('year_received') => $this->values['year_received'] ?? '',
			Meta::key('judgement_year') => $this->values['judgement_year'] ?? '',
		];
	}

	/**
	 * Stores a submitted date as a DATE_FORMAT value, plus the year derived from it.
	 *
	 * @return array<string, string>
	 */
	private function dateMetaArgs(string $field): array
	{
		$date = $this->parseDateField($field);

		if (null === $date) {
			return [];
		}

		return [
			Meta::key($field . '_date') => $date->format(Meta::DATE_FORMAT),
			Meta::key(Meta::DATE_FIELDS[$field]) => $date->format('Y'),
		];
	}

	private function parseDateField(string $field): ?DateTimeImmutable
	{
		if (empty($this->values[$field])) {
			return null;
		}

		try {
			return new DateTimeImmutable($this->values[$field]);
		} catch (Exception) {
			return null;
		}
	}

	/**
	 * If the 'function' field value is 'function_other' and 'function_other' field is not empty,
	 * it returns the value of 'function_other'. Otherwise, it returns the value of 'function'.
	 */
	private function handleFunctionField(): string
	{
		// Guarded because getEnteredValues() array_filters the submission, so an empty
		// field is absent rather than an empty string.
		if ('function_other' === ($this->values['function'] ?? '') && ! empty($this->values['function_other'])) {
			return $this->values['function_other'];
		}

		return $this->values['function'] ?? '';
	}
}

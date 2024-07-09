<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

use DateTime;
use Exception;

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
			'function_other',
			'findings',
			'judgement',
			'conclusion',
			'judgement_date',
			'judgement_year',
		];

		$metaArgs = [];
		foreach ($fields as $field) {
			if (in_array($field, ['date_received', 'judgement_date']) && isset($this->values[$field])) {
				$metaArgs['okl_' . $field] = $this->formatDateField($field);
			} elseif ('function' === $field) {
				$metaArgs['okl_' . $field] = $this->handleFunctionField();
			} else {
				$metaArgs['okl_' . $field] = $this->values[$field] ?? '';
			}
		}

		$metaArgs = array_filter($metaArgs);

		$metaArgs['okl_year_received'] = $this->values['year_received'] ?? '';
		$metaArgs['okl_judgement_year'] = $this->values['judgement_year'] ?? '';

		unset($metaArgs['okl_function_other']);

		return $metaArgs;
	}

	/**
	 * Converts a field value to a DateTime, formats it, and sets the year field for 'date_received' or 'judgement_date'.
	 */
	private function formatDateField(string $field): string
	{
		if (empty($this->values[$field])) {
			return '';
		}

		try {
			$date = new DateTime($this->values[$field]);
		} catch(Exception $e) {
			return '';
		}

		$year = $date->format('Y');

		if ('date_received' === $field) {
			$this->values['year_received'] = $year;
		} elseif ('judgement_date' === $field) {
			$this->values['judgement_year'] = $year;
		}

		return date_i18n('j F Y', $date->getTimestamp());
	}

	/**
	 * If the 'function' field value is 'function_other' and 'function_other' field is not empty,
	 * it returns the value of 'function_other'. Otherwise, it returns the value of 'function'.
	 */
	private function handleFunctionField(): string
	{
		if ('function_other' === $this->values['function'] && ! empty($this->values['function_other'])) {
			return $this->values['function_other'] ?? '';
		}

		return $this->values['function'] ?? '';
	}
}

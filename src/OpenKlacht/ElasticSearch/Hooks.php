<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\ElasticSearch;

use DateTimeImmutable;
use OWC\OpenKlacht\Foundation\Meta;

class Hooks
{
	/**
	 * Meta fields indexed as-is, without a derived counterpart.
	 */
	private const PLAIN_FIELDS = [
		'year_received',
		'judgement_year',
		'judgement',
	];

	public function extendSyncArgs(array $args, int $postID): array
	{
		if ('Klacht' !== $args['doctype']) {
			return $args;
		}

		foreach (self::PLAIN_FIELDS as $field) {
			$args[Meta::key($field)] = get_post_meta($postID, Meta::key($field), true) ?: '';
		}

		foreach (array_keys(Meta::DATE_FIELDS) as $field) {
			$args[Meta::key($field)] = get_post_meta($postID, Meta::key($field), true) ?: '';

			$stored = get_post_meta($postID, Meta::key($field . '_date'), true) ?: '';

			$args[Meta::key($field . '_date')] = $stored;

			$sortable = $this->toSortableDate($stored);

			// Left unset when unparseable
			if (null !== $sortable) {
				$args[Meta::key($field . '_sortable')] = $sortable;
			}
		}

		return $args;
	}

	/**
	 * Reformats the value stored by the datepicker as an ISO-8601 date, so
	 * Elasticsearch maps the field as a date and can sort on it. The leading '!'
	 * zeroes the time component; without it the unspecified fields default to the
	 * current time, which can push the formatted date across a day boundary.
	 */
	private function toSortableDate(string $value): ?string
	{
		$date = DateTimeImmutable::createFromFormat('!' . Meta::DATE_FORMAT, trim($value));

		if (false === $date) {
			return null;
		}

		$errors = DateTimeImmutable::getLastErrors();

		if (false !== $errors && 0 < $errors['warning_count']) {
			return null;
		}

		return $date->format('Y-m-d');
	}
}

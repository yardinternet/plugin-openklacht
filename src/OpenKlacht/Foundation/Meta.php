<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Foundation;

use CMB2;

/**
 * Conventions shared by every consumer of the field definitions in config/: how a
 * field id becomes a meta key, and how definitions are handed to CMB2.
 */
final class Meta
{
	/**
	 * Storage format of the datepicker fields. Declared here because it is a
	 * three-way contract: config/metaboxes.php tells CMB2 to write it, AfterSubmit
	 * writes it on form submission, and ElasticSearch\Hooks parses it back.
	 */
	public const DATE_FORMAT = 'd-m-Y';

	/**
	 * Deprecated free-text date field => the year field derived from its successor.
	 *
	 * The key doubles as the base name of the pair: '<base>_date' is the datepicker
	 * field holding a DATE_FORMAT value, and '<base>_sortable' is the ISO-8601 value
	 * indexed for Elasticsearch -- the key the portal sorts on. The deprecated field
	 * itself is only kept so existing complaints keep their stored value; nothing
	 * reads or writes it.
	 *
	 * @var array<string, string>
	 */
	public const DATE_FIELDS = [
		'date_received' => 'year_received',
		'judgement_date' => 'judgement_year',
	];

	/**
	 * Prefixes a field id from the config to the meta key it is stored under.
	 */
	public static function key(string $field): string
	{
		return sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field);
	}

	/**
	 * Adds config-declared field definitions to a CMB2 box, prefixing their ids.
	 * Definitions are passed through whole so field types can carry their own
	 * options ('date_format', 'desc', ...); CMB2 ignores keys it does not recognise.
	 *
	 * @param array<mixed> $fields
	 */
	public static function addFields(CMB2 $box, array $fields): void
	{
		foreach ($fields as $field) {
			if (! is_array($field)) {
				continue;
			}

			if (isset($field['id'])) {
				$field['id'] = self::key($field['id']);
			}

			$box->add_field($field);
		}
	}
}

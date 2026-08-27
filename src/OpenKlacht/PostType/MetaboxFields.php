<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\PostType;

class MetaboxFields
{
	public function __construct(protected readonly array $fields)
	{
	}

	public function createMetaFields(): void
	{
		$metaboxes = new_cmb2_box([
			'id' => 'klacht_metabox',
			'title' => __('Gegevens', 'openklacht'),
			'object_types' => [
				'openklacht',
			],
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
		]);

		foreach ($this->fields as $field) {
			// The whole definition is passed through so field types can carry their
			// own options ('date_format', 'desc', 'attributes', ...). CMB2 ignores
			// keys it does not recognise for a given type.
			$field['id'] = sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']);

			$metaboxes->add_field($field);
		}
	}
}

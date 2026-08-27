<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\PostType;

use OWC\OpenKlacht\Foundation\Meta;

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

		Meta::addFields($metaboxes, $this->fields);
	}
}

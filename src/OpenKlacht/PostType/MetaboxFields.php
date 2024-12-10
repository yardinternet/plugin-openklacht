<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\PostType;

class MetaboxFields
{
    protected array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

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
            $fieldData = [
                'name' => $field['name'],
                'id' => sprintf('%s_%s', OWC_OPENKLACHT_PREFIX, $field['id']),
                'type' => $field['type'],
            ];

            if ('select' === $field['type'] && isset($field['options'])) {
                $fieldData['options'] = $field['options'];
            }

            $metaboxes->add_field($fieldData);
        }
    }
}

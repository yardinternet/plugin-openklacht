<?php

declare(strict_types=1);

namespace Yard\OpenKlacht;

class OpenKlachtFields
{
    public function createMetaFields()
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

        $fields = [
            ['name' => 'Kenmerk', 'id' => 'reference', 'type' => 'text'],
            ['name' => 'Naam van de klacht', 'id' => 'title', 'type' => 'text'],
            ['name' => 'Ontvangstdatum', 'id' => 'date_received', 'type' => 'text_date'],
            ['name' => 'Betrokken organisatie onderdeel', 'id' => 'organization', 'type' => 'text'],
            ['name' => 'Functiebenaming ambtenaar over wie geklaagd is', 'id' => 'function', 'type' => 'text'],
            ['name' => 'Omschrijving', 'id' => 'description', 'type' => 'textarea'],
            ['name' => 'Bevindingen/ oorzaak', 'id' => 'findings', 'type' => 'select', 'options' =>
                ['gegrond' => 'Gegrond', 'ongegrond' => 'Ongegrond', 'niet_ontvankelijk' => 'Niet ontvankelijk',],
            ],
            ['name' => 'Het oordeel', 'id' => 'judgement', 'type' => 'textarea'],
            ['name' => 'De conclusie', 'id' => 'conclusion', 'type' => 'textarea'],
            ['name' => 'Dagtekening van het oordeel', 'id' => 'judgement_date', 'type' => 'text_date'],
            ['name' => 'Dossiernummer', 'id' => 'file_number', 'type' => 'text'],
        ];

        foreach ($fields as $field) {
            $fieldData = [
                'name' => __($field['name'], 'openklacht'),
                'id' => sprintf('okl_%s', $field['id']),
                'type' => $field['type'],
            ];

            if ('select' === $field['type'] && isset($field['options'])) {
                $fieldData['options'] = $field['options'];
            }

            $metaboxes->add_field($fieldData);
        }
    }
}

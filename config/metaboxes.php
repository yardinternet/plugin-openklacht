<?php

return [
    ['name' => 'Kenmerk', 'id' => 'reference', 'type' => 'text'],
    ['name' => 'Onderwerp', 'id' => 'title', 'type' => 'text'],
    ['name' => 'Ontvangstdatum', 'id' => 'date_received', 'type' => 'text'],
    ['name' => 'Betrokken organisatie onderdeel', 'id' => 'organization', 'type' => 'text'],
    ['name' => 'Functiebenaming ambtenaar over wie geklaagd is', 'id' => 'function', 'type' => 'text'],
    ['name' => 'Omschrijving', 'id' => 'description', 'type' => 'wysiwyg'],
    ['name' => 'Bevinding', 'id' => 'findings', 'type' => 'wysiwyg'],
    ['name' => 'Oordeel', 'id' => 'judgement', 'type' => 'select', 'options' =>
        [
            'Gegrond' => 'Gegrond',
            'Deels gegrond, deels ongegrond' => 'Deels gegrond, deels ongegrond',
            'Ongegrond' => 'Ongegrond',
            'Niet ontvankelijk' => 'Niet ontvankelijk',
        ],
    ],
    ['name' => 'Conclusie', 'id' => 'conclusion', 'type' => 'wysiwyg'],
    ['name' => 'Dagtekening van het oordeel', 'id' => 'judgement_date', 'type' => 'text'],
];

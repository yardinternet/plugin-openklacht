<?php

return [
    ['name' => 'Kenmerk', 'id' => 'reference', 'type' => 'text'],
    ['name' => 'Onderwerp', 'id' => 'title', 'type' => 'text'],
    ['name' => 'Ontvangstdatum', 'id' => 'date_received', 'type' => 'text'],
    ['name' => 'Betrokken organisatie onderdeel', 'id' => 'organization', 'type' => 'text'],
    ['name' => 'Functiebenaming ambtenaar over wie geklaagd is', 'id' => 'function', 'type' => 'text'],
    ['name' => 'Omschrijving', 'id' => 'description', 'type' => 'textarea'],
	['name' => 'Bevinding', 'id' => 'findings', 'type' => 'textarea'],
	['name' => 'Oordeel', 'id' => 'judgement', 'type' => 'select', 'options' =>
        [
			'Gegrond' => 'Gegrond',
			'Ongegrond' => 'Ongegrond',
			'Niet ontvankelijk' => 'Niet ontvankelijk',
			'Gedeeltelijk gegrond' => 'Gedeeltelijk gegrond',
			'Gedeeltelijk ongegrond' => 'Gedeeltelijk ongegrond',
		],
    ],
    ['name' => 'Conclusie', 'id' => 'conclusion', 'type' => 'textarea'],
    ['name' => 'Dagtekening van het oordeel', 'id' => 'judgement_date', 'type' => 'text']
];

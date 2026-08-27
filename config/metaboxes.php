<?php

declare(strict_types=1);

return [
	['name' => __('Kenmerk', 'openklacht'), 'id' => 'reference', 'type' => 'text'],
	['name' => __('Onderwerp', 'openklacht'), 'id' => 'title', 'type' => 'text'],
	[
		'name' => __('Ontvangstdatum', 'openklacht'),
		'id' => 'date_received_date',
		'type' => 'text_date',
		'date_format' => 'd-m-Y',
	],
	[
		'name' => __('Ontvangstdatum (verouderd)', 'openklacht'),
		'id' => 'date_received',
		'type' => 'text',
		'desc' => __('Verouderd veld, alleen nog in gebruik voor bestaande klachten. Vul hierboven de Ontvangstdatum in.', 'openklacht'),
	],
	['name' => __('Betrokken organisatie onderdeel', 'openklacht'), 'id' => 'organization', 'type' => 'text'],
	['name' => __('Functiebenaming ambtenaar over wie geklaagd is', 'openklacht'), 'id' => 'function', 'type' => 'text'],
	['name' => __('Omschrijving', 'openklacht'), 'id' => 'description', 'type' => 'wysiwyg'],
	['name' => __('Bevinding', 'openklacht'), 'id' => 'findings', 'type' => 'wysiwyg'],
	['name' => __('Oordeel', 'openklacht'), 'id' => 'judgement', 'type' => 'select', 'options' =>
		[
			'Gegrond' => __('Gegrond', 'openklacht'),
			'Deels gegrond, deels ongegrond' => __('Deels gegrond, deels ongegrond', 'openklacht'),
			'Ongegrond' => __('Ongegrond', 'openklacht'),
			'Niet ontvankelijk' => __('Niet ontvankelijk', 'openklacht'),
		],
	],
	['name' => __('Conclusie', 'openklacht'), 'id' => 'conclusion', 'type' => 'wysiwyg'],
	[
		'name' => __('Dagtekening van het oordeel', 'openklacht'),
		'id' => 'judgement_date_date',
		'type' => 'text_date',
		'date_format' => 'd-m-Y',
	],
	[
		'name' => __('Dagtekening van het oordeel (verouderd)', 'openklacht'),
		'id' => 'judgement_date',
		'type' => 'text',
		'desc' => __('Verouderd veld, alleen nog in gebruik voor bestaande klachten. Vul hierboven de Dagtekening van het oordeel in.', 'openklacht'),
	],
];

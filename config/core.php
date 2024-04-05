<?php

return [
    'providers' => [
        OWC\OpenKlacht\RestAPI\RestAPIServiceProvider::class,
        OWC\OpenKlacht\Admin\AdminServiceProvider::class,
        OWC\OpenKlacht\GravityForms\GravityFormsServiceProvider::class,
        OWC\OpenKlacht\PostType\PostTypeServiceProvider::class,
        OWC\OpenKlacht\ElasticSearch\ElasticSearchServiceProvider::class,
        OWC\OpenKlacht\Settings\SettingsServiceProvider::class,
    ],
];

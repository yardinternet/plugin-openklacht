<?php

return [
    'openklacht-base' => [
        'id' => '_owc_openklacht_base_settings',
        'title' => __('OpenKlacht instellingen', 'openklacht'),
        'object_types' => ['options-page'],
        'option_key' => '_owc_openklacht_base_settings',
        'tab_group' => 'openklacht-base',
        'tab_title' => __('Algemeen', 'openklacht'),
        'position' => 30,
        'icon_url' => 'dashicons-admin-settings',
        'fields' => [
            'openklacht_portal_url' => [
                'name' => __('Portaal URL', 'openklacht'),
                'desc' => __('URL inclusief http(s)://', 'openklacht'),
                'id' => 'setting_openklacht_portal_url',
                'type' => 'text',
            ],
            'openklacht_item_slug' => [
                'name' => __('Portaal OpenKlacht item slug', 'openklacht'),
                'desc' => __("URL voor OpenKlacht items in het portaal, bijv. 'onderwerp'", 'openklacht'),
                'id' => 'setting_openklacht_item_slug',
                'type' => 'text',
            ],
            'openklacht_use_portal_url' => [
                'name' => __('Portaal URL (API)', 'openklacht'),
                'desc' => __('Gebruik het portaal URL in de REST API.', 'openklacht'),
                'id' => 'setting_openklacht_use_portal_url',
                'type' => 'checkbox',
            ],
        ],
    ],
];

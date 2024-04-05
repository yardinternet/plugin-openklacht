<?php

return [
    'base' => [
        'id' => '_owc_openklacht_base_settings',
        'title' => __('OpenKlacht settings', 'openklacht'),
        'object_types' => ['options-page'],
        'option_key' => '_owc_openklacht_base_settings',
        'tab_group' => 'base',
        'tab_title' => __('General', 'openklacht'),
        'position' => 30,
        'icon_url' => 'dashicons-admin-settings',
        'fields' => [
            'openklacht_portal_url' => [
                'name' => __('Portal URL', 'openklacht'),
                'desc' => __('URL including http(s)://', 'openklacht'),
                'id' => 'setting_openklacht_portal_url',
                'type' => 'text',
            ],
            'openklacht_item_slug' => [
                'name' => __('Portal OpenKlacht item slug', 'openklacht'),
                'desc' => __('URL for OpenKlacht items in the portal, eg "onderwerp"', 'openklacht'),
                'id' => 'setting_openklacht_item_slug',
                'type' => 'text',
            ],
            'openklacht_use_portal_url' => [
                'name' => __('Portal url', 'openklacht'),
                'desc' => __('Use portal url in api.', 'openklacht'),
                'id' => 'setting_openklacht_use_portal_url',
                'type' => 'checkbox',
            ],
        ],
    ],
];

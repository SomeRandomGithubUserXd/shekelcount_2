<?php

return [
    [
        'name' => 'Category Switcher',
        'description' => 'Incoming category entries will be automatically moved to created or existing category',
        'params' => [
            [
                'name' => 'incoming_category_name',
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            [
                'name' => 'target_category_name',
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
            [
                'name' => 'do_not_import_incoming_category',
                'type' => 'checkbox',
                'rules' => ['nullable', 'bool']
            ],
        ],
        'class' => \App\Services\ImportEntries\Mutators\Types\CategorySwitcher::class
    ],
    [
        'name' => 'Category Skipper',
        'description' => 'Specified category will not be imported',
        'params' => [
            [
                'name' => 'category_name',
                'type' => 'text',
                'rules' => ['required', 'string']
            ],
        ],
        'class' => \App\Services\ImportEntries\Mutators\Types\CategorySkipper::class
    ],
];

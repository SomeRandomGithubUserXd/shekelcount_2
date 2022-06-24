<?php

return [
    "available_banks" => [
        [
            "name" => "Tinkoff",
            "logo" => "/images/logos/tinkoff.png",
            "parser" => \App\Services\ImportEntries\Banks\TinkoffEntryParser::class
        ]
    ]
];

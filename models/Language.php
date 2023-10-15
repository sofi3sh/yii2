<?php

namespace app\models;

class Language
{
    const EN_ID = 1;
    const UK_ID = 2;

    const LANGUAGES_LIST = [
        self::EN_ID => [
            'code' => 'en-US',
            'title' => 'English'
        ],
        self::UK_ID => [
            'code' => 'uk',
            'title' => 'Українська'
        ]
    ];

}

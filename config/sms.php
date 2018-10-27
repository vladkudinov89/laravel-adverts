<?php

return [
    // "sms.ru" , "array"
//    'driver' => env('SMS_DRIVER' , 'sms.ru'),
    'driver' => env('SMS_DRIVER' , 'array'),

    'drivers' => [
        'sms.ru' => [
            'app_id' => env('SMS_RU_APP_ID'),
            'url' => env('SMS_RU_URL')
        ]
    ]

];
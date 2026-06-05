<?php

return [
    [
        'key'    => 'sales.payment_methods.creditpayment',
        'name'   => 'CreditPayment', // use translation
        'info'   => 'Information about CreditPayment', // use translation
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Title', // use translation
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Description', // use translation
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'Status', // use translation
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];
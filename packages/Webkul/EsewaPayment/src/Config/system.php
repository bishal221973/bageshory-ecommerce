<?php

return [
    [
        'key'    => 'sales.payment_methods.esewapayment',
        'name'   => 'EsewaPayment', // use translation
        'info'   => 'Information about EsewaPayment', // use translation
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
            ],
            [
                'name'          => 'baseUrl',
                'title'         => 'Base URL', // use translation
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'productCode',
                'title'         => 'Product Code', // use translation
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'successUrl',
                'title'         => 'Success URL', // use translation
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'failUrl',
                'title'         => 'Fail URL', // use translation
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];
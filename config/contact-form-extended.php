<?php

return [
    'fields' => [
        'fromName'  => [
            'name'    => 'Name',
            'filters' => 'trim',
            'rules'   => 'required'
        ],
        'fromEmail' => [
            'name'    => 'E-Mail',
            'filters' => 'trim|sanitize_email',
            'rules'   => 'required|valid_email'
        ],
        'company'   => [
            'name'    => 'Company',
            'filters' => 'trim',
            'rules'   => 'required'
        ],
        'enquiry'   => [
            'name'    => 'Message',
            'filters' => 'trim',
            'rules'   => 'required'
        ]
    ]
];

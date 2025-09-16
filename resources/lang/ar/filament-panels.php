<?php

return [
    'actions' => [
        'logout' => [
            'label' => 'تسجيل الخروج',
        ],
    ],
    
    'pages' => [
        'auth' => [
            'login' => [
                'form' => [
                    'email' => [
                        'label' => 'البريد الإلكتروني',
                    ],
                    'password' => [
                        'label' => 'كلمة المرور',
                    ],
                    'remember' => [
                        'label' => 'تذكرني',
                    ],
                ],
                'actions' => [
                    'authenticate' => [
                        'label' => 'تسجيل الدخول',
                    ],
                ],
            ],
        ],
    ],
    
    'resources' => [
        'pages' => [
            'create_record' => [
                'title' => 'إنشاء :label',
                'actions' => [
                    'create' => [
                        'label' => 'إنشاء',
                    ],
                    'create_another' => [
                        'label' => 'إنشاء وإنشاء آخر',
                    ],
                ],
            ],
            'edit_record' => [
                'title' => 'تعديل :label',
                'actions' => [
                    'save' => [
                        'label' => 'حفظ التغييرات',
                    ],
                ],
            ],
            'list_records' => [
                'actions' => [
                    'create' => [
                        'label' => 'جديد :label',
                    ],
                ],
            ],
        ],
    ],
];
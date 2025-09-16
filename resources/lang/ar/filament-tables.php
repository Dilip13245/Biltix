<?php

return [
    'columns' => [
        'text' => [
            'actions' => [
                'collapse_list' => 'إظهار :count أقل',
                'expand_list' => 'إظهار :count أكثر',
            ],
        ],
    ],

    'filters' => [
        'actions' => [
            'remove' => 'إزالة المرشح',
            'remove_all' => 'إزالة جميع المرشحات',
            'reset' => 'إعادة تعيين',
        ],
        'multi_select' => [
            'placeholder' => 'الكل',
        ],
        'select' => [
            'placeholder' => 'الكل',
        ],
        'trashed' => [
            'label' => 'السجلات المحذوفة',
            'only_trashed' => 'المحذوفة فقط',
            'with_trashed' => 'مع المحذوفة',
            'without_trashed' => 'بدون المحذوفة',
        ],
    ],

    'actions' => [
        'attach' => [
            'single' => [
                'label' => 'إرفاق',
            ],
            'multiple' => [
                'label' => 'إرفاق المحدد',
            ],
        ],
        'bulk_actions' => [
            'label' => 'الإجراءات المجمعة',
        ],
        'delete' => [
            'single' => [
                'label' => 'حذف',
                'modal' => [
                    'heading' => 'حذف :label',
                    'actions' => [
                        'delete' => [
                            'label' => 'حذف',
                        ],
                    ],
                ],
            ],
            'multiple' => [
                'label' => 'حذف المحدد',
                'modal' => [
                    'heading' => 'حذف :label المحدد',
                    'actions' => [
                        'delete' => [
                            'label' => 'حذف',
                        ],
                    ],
                ],
            ],
        ],
        'detach' => [
            'single' => [
                'label' => 'إلغاء الإرفاق',
            ],
            'multiple' => [
                'label' => 'إلغاء إرفاق المحدد',
            ],
        ],
        'edit' => [
            'single' => [
                'label' => 'تعديل',
            ],
        ],
        'view' => [
            'single' => [
                'label' => 'عرض',
            ],
        ],
    ],

    'empty' => [
        'heading' => 'لا توجد :model',
        'description' => 'أنشئ :model للبدء.',
    ],

    'pagination' => [
        'label' => 'التنقل بين الصفحات',
        'overview' => 'عرض :first إلى :last من :total نتيجة',
        'fields' => [
            'records_per_page' => [
                'label' => 'لكل صفحة',
                'options' => [
                    'all' => 'الكل',
                ],
            ],
        ],
        'actions' => [
            'go_to_page' => [
                'label' => 'الذهاب إلى الصفحة :page',
            ],
            'next' => [
                'label' => 'التالي',
            ],
            'previous' => [
                'label' => 'السابق',
            ],
        ],
    ],

    'reorder_indicator' => 'اسحب وأفلت السجلات لإعادة ترتيبها.',

    'selection_indicator' => [
        'selected_count' => 'تم تحديد سجل واحد|تم تحديد :count سجل',
        'actions' => [
            'select_all' => [
                'label' => 'تحديد جميع :count',
            ],
            'deselect_all' => [
                'label' => 'إلغاء تحديد الكل',
            ],
        ],
    ],

    'sorting' => [
        'fields' => [
            'column' => [
                'label' => 'ترتيب حسب',
            ],
            'direction' => [
                'label' => 'اتجاه الترتيب',
                'options' => [
                    'asc' => 'تصاعدي',
                    'desc' => 'تنازلي',
                ],
            ],
        ],
    ],
];
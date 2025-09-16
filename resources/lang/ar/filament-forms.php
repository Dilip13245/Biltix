<?php

return [
    'fields' => [
        'color_picker' => [
            'hex_field' => [
                'label' => 'اللون السادس عشر',
            ],
        ],
        'date_time_picker' => [
            'actions' => [
                'clear' => [
                    'label' => 'مسح',
                ],
                'now' => [
                    'label' => 'الآن',
                ],
            ],
        ],
        'file_upload' => [
            'editor' => [
                'actions' => [
                    'cancel' => [
                        'label' => 'إلغاء',
                    ],
                    'drag_crop' => [
                        'label' => 'وضع السحب "قص"',
                    ],
                    'drag_move' => [
                        'label' => 'وضع السحب "نقل"',
                    ],
                    'flip_horizontal' => [
                        'label' => 'قلب الصورة أفقياً',
                    ],
                    'flip_vertical' => [
                        'label' => 'قلب الصورة عمودياً',
                    ],
                    'move_down' => [
                        'label' => 'تحريك الصورة لأسفل',
                    ],
                    'move_left' => [
                        'label' => 'تحريك الصورة لليسار',
                    ],
                    'move_right' => [
                        'label' => 'تحريك الصورة لليمين',
                    ],
                    'move_up' => [
                        'label' => 'تحريك الصورة لأعلى',
                    ],
                    'reset' => [
                        'label' => 'إعادة تعيين',
                    ],
                    'rotate_left' => [
                        'label' => 'تدوير الصورة لليسار',
                    ],
                    'rotate_right' => [
                        'label' => 'تدوير الصورة لليمين',
                    ],
                    'set_aspect_ratio' => [
                        'label' => 'تعيين نسبة العرض إلى الارتفاع إلى :ratio',
                    ],
                    'save' => [
                        'label' => 'حفظ',
                    ],
                    'zoom_100' => [
                        'label' => 'تكبير الصورة إلى 100%',
                    ],
                    'zoom_in' => [
                        'label' => 'تكبير',
                    ],
                    'zoom_out' => [
                        'label' => 'تصغير',
                    ],
                ],
            ],
        ],
        'key_value' => [
            'actions' => [
                'add' => [
                    'label' => 'إضافة صف',
                ],
                'delete' => [
                    'label' => 'حذف صف',
                ],
                'reorder' => [
                    'label' => 'إعادة ترتيب الصف',
                ],
            ],
            'fields' => [
                'key' => [
                    'label' => 'المفتاح',
                ],
                'value' => [
                    'label' => 'القيمة',
                ],
            ],
        ],
        'markdown_editor' => [
            'toolbar_buttons' => [
                'attach_files' => 'إرفاق ملفات',
                'blockquote' => 'اقتباس',
                'bold' => 'عريض',
                'bullet_list' => 'قائمة نقطية',
                'code_block' => 'كتلة كود',
                'heading' => 'عنوان',
                'italic' => 'مائل',
                'link' => 'رابط',
                'ordered_list' => 'قائمة مرقمة',
                'redo' => 'إعادة',
                'strike' => 'يتوسطه خط',
                'table' => 'جدول',
                'undo' => 'تراجع',
            ],
        ],
        'repeater' => [
            'actions' => [
                'add' => [
                    'label' => 'إضافة إلى :label',
                ],
                'add_between' => [
                    'label' => 'إدراج بين العناصر',
                ],
                'delete' => [
                    'label' => 'حذف',
                ],
                'clone' => [
                    'label' => 'استنساخ',
                ],
                'move_down' => [
                    'label' => 'تحريك لأسفل',
                ],
                'move_up' => [
                    'label' => 'تحريك لأعلى',
                ],
                'collapse' => [
                    'label' => 'طي',
                ],
                'expand' => [
                    'label' => 'توسيع',
                ],
                'collapse_all' => [
                    'label' => 'طي الكل',
                ],
                'expand_all' => [
                    'label' => 'توسيع الكل',
                ],
            ],
        ],
        'rich_editor' => [
            'dialogs' => [
                'link' => [
                    'actions' => [
                        'link' => 'رابط',
                        'unlink' => 'إلغاء الرابط',
                    ],
                    'label' => 'URL',
                    'placeholder' => 'أدخل URL',
                ],
            ],
            'toolbar_buttons' => [
                'attach_files' => 'إرفاق ملفات',
                'blockquote' => 'اقتباس',
                'bold' => 'عريض',
                'bullet_list' => 'قائمة نقطية',
                'code_block' => 'كتلة كود',
                'h1' => 'عنوان',
                'h2' => 'عنوان فرعي',
                'h3' => 'عنوان فرعي',
                'italic' => 'مائل',
                'link' => 'رابط',
                'ordered_list' => 'قائمة مرقمة',
                'redo' => 'إعادة',
                'strike' => 'يتوسطه خط',
                'underline' => 'تحته خط',
                'undo' => 'تراجع',
            ],
        ],
        'select' => [
            'actions' => [
                'create_option' => [
                    'modal' => [
                        'heading' => 'إنشاء',
                        'actions' => [
                            'create' => [
                                'label' => 'إنشاء',
                            ],
                            'create_another' => [
                                'label' => 'إنشاء وإنشاء آخر',
                            ],
                        ],
                    ],
                ],
                'edit_option' => [
                    'modal' => [
                        'heading' => 'تعديل',
                        'actions' => [
                            'save' => [
                                'label' => 'حفظ',
                            ],
                        ],
                    ],
                ],
            ],
            'boolean' => [
                'true' => 'نعم',
                'false' => 'لا',
            ],
            'loading_message' => 'جاري التحميل...',
            'max_items_message' => 'يمكن تحديد :count فقط.',
            'no_search_results_message' => 'لا توجد خيارات تطابق بحثك.',
            'placeholder' => 'اختر خياراً',
            'searching_message' => 'جاري البحث...',
            'search_prompt' => 'ابدأ الكتابة للبحث...',
        ],
        'tags_input' => [
            'placeholder' => 'علامات جديدة',
        ],
        'text_input' => [
            'actions' => [
                'hide_password' => [
                    'label' => 'إخفاء كلمة المرور',
                ],
                'show_password' => [
                    'label' => 'إظهار كلمة المرور',
                ],
            ],
        ],
        'toggle' => [
            'boolean' => [
                'true' => 'نعم',
                'false' => 'لا',
            ],
        ],
        'wizard' => [
            'actions' => [
                'previous_step' => [
                    'label' => 'السابق',
                ],
                'next_step' => [
                    'label' => 'التالي',
                ],
            ],
        ],
    ],

    'actions' => [
        'action' => [
            'label' => 'إجراء',
        ],
        'cancel' => [
            'label' => 'إلغاء',
        ],
        'create' => [
            'label' => 'إنشاء',
        ],
        'delete' => [
            'label' => 'حذف',
        ],
        'edit' => [
            'label' => 'تعديل',
        ],
        'open_url' => [
            'label' => 'فتح URL',
        ],
        'replicate' => [
            'label' => 'تكرار',
        ],
        'save' => [
            'label' => 'حفظ',
        ],
        'view' => [
            'label' => 'عرض',
        ],
    ],
];
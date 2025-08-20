<?php

return [
    'auth' => [
        // Validation Messages
        'email_required' => 'البريد الإلكتروني مطلوب',
        'email_invalid' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
        'email_unique' => 'هذا البريد الإلكتروني مسجل بالفعل',
        'phone_number_required' => 'رقم الهاتف مطلوب',
        'phone_number_unique' => 'رقم الهاتف هذا مسجل بالفعل',
        'name_required' => 'الاسم مطلوب',
        'password_required' => 'كلمة المرور مطلوبة',
        'password_min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        'role_required' => 'الدور مطلوب',
        'company_name_required' => 'اسم الشركة مطلوب',
        'device_type_required' => 'نوع الجهاز مطلوب',
        'type_required' => 'النوع مطلوب',
        'type_invalid' => 'نوع غير صحيح',
        'otp_required' => 'رمز التحقق مطلوب',
        'new_password_required' => 'كلمة المرور الجديدة مطلوبة',
        'new_password_min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل',
        'confirm_password_required' => 'تأكيد كلمة المرور مطلوب',
        'confirm_password_mismatch' => 'كلمات المرور غير متطابقة',
        'user_id_required' => 'معرف المستخدم مطلوب',

        // Success Messages
        'signup_success' => 'تم إنشاء الحساب بنجاح',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'otp_sent' => 'تم إرسال رمز التحقق بنجاح',
        'otp_verified_success' => 'تم التحقق من الرمز بنجاح',
        'password_reset_success' => 'تم إعادة تعيين كلمة المرور بنجاح',
        'profile_updated_success' => 'تم تحديث الملف الشخصي بنجاح',
        'logout_success' => 'تم تسجيل الخروج بنجاح',
        'account_deleted' => 'تم حذف الحساب بنجاح',
        'user_profile_retrieved' => 'تم استرداد الملف الشخصي بنجاح',

        // Error Messages
        'signup_error' => 'فشل في إنشاء الحساب',
        'user_not_available' => 'المستخدم غير موجود',
        'invalid_password' => 'كلمة مرور غير صحيحة',
        'user_not_found_or_inactive' => 'المستخدم غير موجود أو غير نشط',
        'user_already_exists' => 'المستخدم موجود بالفعل',
        'user_inactive_contact_admin' => 'حساب المستخدم غير نشط، اتصل بالمدير',
        'invalid_otp' => 'رمز التحقق غير صحيح',
        'otp_email_failed' => 'فشل في إرسال رمز التحقق',
        'password_same_as_old' => 'كلمة المرور الجديدة لا يمكن أن تكون نفس القديمة',
        'user_not_found' => 'المستخدم غير موجود',
        'invalid_user' => 'مستخدم غير صحيح',
        'account_not_active' => 'الحساب غير نشط',
    ],

    'general' => [
        'success' => 'نجح',
        'error' => 'حدث خطأ',
        'not_found' => 'غير موجود',
        'unauthorized' => 'وصول غير مصرح به',
        'validation_error' => 'خطأ في التحقق',
        'internal_error' => 'خطأ داخلي في الخادم',
    ],

    'projects' => [
        'created_success' => 'تم إنشاء المشروع بنجاح',
        'updated_success' => 'تم تحديث المشروع بنجاح',
        'deleted_success' => 'تم حذف المشروع بنجاح',
        'not_found' => 'المشروع غير موجود',
        'list_retrieved' => 'تم استرداد قائمة المشاريع بنجاح',
        'details_retrieved' => 'تم استرداد تفاصيل المشروع بنجاح',
        'stats_retrieved' => 'تم استرداد إحصائيات المشروع بنجاح',
    ],

    'tasks' => [
        'created_success' => 'تم إنشاء المهمة بنجاح',
        'updated_success' => 'تم تحديث المهمة بنجاح',
        'deleted_success' => 'تم حذف المهمة بنجاح',
        'status_changed' => 'تم تغيير حالة المهمة بنجاح',
        'comment_added' => 'تم إضافة التعليق بنجاح',
        'progress_updated' => 'تم تحديث تقدم المهمة بنجاح',
        'not_found' => 'المهمة غير موجودة',
        'list_retrieved' => 'تم استرداد قائمة المهام بنجاح',
        'details_retrieved' => 'تم استرداد تفاصيل المهمة بنجاح',
    ],

    'inspections' => [
        'created_success' => 'تم إنشاء التفتيش بنجاح',
        'completed_success' => 'تم إكمال التفتيش بنجاح',
        'approved_success' => 'تم اعتماد التفتيش بنجاح',
        'not_found' => 'التفتيش غير موجود',
        'templates_retrieved' => 'تم استرداد قوالب التفتيش بنجاح',
        'list_retrieved' => 'تم استرداد قائمة التفتيشات بنجاح',
        'results_retrieved' => 'تم استرداد نتائج التفتيش بنجاح',
    ],

    'snags' => [
        'created_success' => 'تم الإبلاغ عن المشكلة بنجاح',
        'updated_success' => 'تم تحديث المشكلة بنجاح',
        'resolved_success' => 'تم حل المشكلة بنجاح',
        'assigned_success' => 'تم تعيين المشكلة بنجاح',
        'comment_added' => 'تم إضافة التعليق بنجاح',
        'not_found' => 'المشكلة غير موجودة',
        'list_retrieved' => 'تم استرداد قائمة المشاكل بنجاح',
        'categories_retrieved' => 'تم استرداد فئات المشاكل بنجاح',
    ],

    'plans' => [
        'uploaded_success' => 'تم رفع المخطط بنجاح',
        'deleted_success' => 'تم حذف المخطط بنجاح',
        'markup_added' => 'تم إضافة التعليق التوضيحي بنجاح',
        'approved_success' => 'تم اعتماد المخطط بنجاح',
        'not_found' => 'المخطط غير موجود',
        'list_retrieved' => 'تم استرداد قائمة المخططات بنجاح',
        'markups_retrieved' => 'تم استرداد تعليقات المخطط بنجاح',
    ],

    'files' => [
        'uploaded_success' => 'تم رفع الملف بنجاح',
        'deleted_success' => 'تم حذف الملف بنجاح',
        'not_found' => 'الملف غير موجود',
        'list_retrieved' => 'تم استرداد قائمة الملفات بنجاح',
        'categories_retrieved' => 'تم استرداد فئات الملفات بنجاح',
    ],

    'notifications' => [
        'marked_read' => 'تم وضع علامة مقروء على الإشعار',
        'all_marked_read' => 'تم وضع علامة مقروء على جميع الإشعارات',
        'deleted_success' => 'تم حذف الإشعار بنجاح',
        'settings_retrieved' => 'تم استرداد إعدادات الإشعارات بنجاح',
        'settings_updated' => 'تم تحديث إعدادات الإشعارات بنجاح',
        'list_retrieved' => 'تم استرداد قائمة الإشعارات بنجاح',
    ],
];

// Auth translations
return [
    'token_not_found' => 'الرمز المميز غير موجود',
    'invalid_token' => 'رمز مميز غير صحيح',
    'invalid_api_key' => 'مفتاح API غير صحيح',
    'api_key_not_found' => 'مفتاح API غير موجود',
];
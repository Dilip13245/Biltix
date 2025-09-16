<?php

return [
    // Login validation
    'email_required' => 'عنوان البريد الإلكتروني مطلوب',
    'email_invalid' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
    'password_required' => 'كلمة المرور مطلوبة',
    'password_min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
    
    // Register validation
    'full_name_required' => 'الاسم الكامل مطلوب',
    'full_name_min' => 'الاسم الكامل يجب أن يكون حرفين على الأقل',
    'phone_required' => 'رقم الهاتف مطلوب',
    'phone_invalid' => 'يرجى إدخال رقم هاتف صحيح',
    'password_confirm_required' => 'يرجى تأكيد كلمة المرور',
    'password_mismatch' => 'كلمات المرور غير متطابقة',
    'company_name_required' => 'اسم الشركة مطلوب',
    'employee_count_required' => 'عدد الموظفين مطلوب',
    'employee_count_min' => 'عدد الموظفين يجب أن يكون 1 على الأقل',
    'designation_required' => 'يرجى اختيار منصبك',
    
    // Forgot password validation
    'mobile_required' => 'رقم الجوال مطلوب',
    'mobile_invalid' => 'يرجى إدخال رقم جوال صحيح',
    'otp_required' => 'رمز التحقق مطلوب',
    'otp_invalid' => 'يرجى إدخال رمز تحقق صحيح مكون من 6 أرقام',
    'new_password_required' => 'كلمة المرور الجديدة مطلوبة',
    'new_password_min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل',
    'confirm_password_required' => 'يرجى تأكيد كلمة المرور الجديدة',
    'new_password_mismatch' => 'كلمات المرور الجديدة غير متطابقة',
    
    // Role validations
    'role_name_format' => 'اسم الدور يجب أن يحتوي على أحرف صغيرة وشرطة سفلية فقط',
    'role_name_min' => 'اسم الدور يجب أن يكون 3 أحرف على الأقل',
    'display_name_format' => 'الاسم المعروض يمكن أن يحتوي على أحرف ومسافات فقط',
    'display_name_min' => 'الاسم المعروض يجب أن يكون حرفين على الأقل',
    'description_min' => 'الوصف يجب أن يكون 10 أحرف على الأقل',
    'description_max' => 'الوصف لا يمكن أن يتجاوز 500 حرف',
    'permissions_required' => 'يجب اختيار صلاحية واحدة على الأقل',
    'permissions_min' => 'يرجى اختيار صلاحية واحدة على الأقل لهذا الدور',
    
    // Permission validations
    'permission_name_format' => 'اسم الصلاحية يجب أن يحتوي على أحرف صغيرة وشرطة سفلية ونقاط فقط',
    'permission_name_min' => 'اسم الصلاحية يجب أن يكون 3 أحرف على الأقل',
    'permission_name_unique' => 'اسم الصلاحية هذا موجود بالفعل',
    'permission_display_format' => 'الاسم المعروض يمكن أن يحتوي على أحرف ومسافات وشرطات فقط',
    'permission_display_min' => 'الاسم المعروض يجب أن يكون 3 أحرف على الأقل',
    'permission_description_min' => 'الوصف يجب أن يكون 5 أحرف على الأقل',
    'permission_description_max' => 'الوصف لا يمكن أن يتجاوز 300 حرف',
    
    // Success messages
    'role_created' => 'تم إنشاء الدور بنجاح!',
    'role_updated' => 'تم تحديث الدور بنجاح!',
    'role_deleted' => 'تم حذف الدور بنجاح!',
    'permission_created' => 'تم إنشاء الصلاحية بنجاح!',
    'permission_updated' => 'تم تحديث الصلاحية بنجاح!',
    'permission_deleted' => 'تم حذف الصلاحية بنجاح!',
    
    // Error messages
    'operation_failed' => 'فشلت العملية. يرجى المحاولة مرة أخرى.',
    'delete_failed' => 'لا يمكن حذف هذا العنصر لأنه قيد الاستخدام.',
    
    // Help & Support messages
    'support_created' => 'تم إنشاء تذكرة الدعم بنجاح!',
    'support_updated' => 'تم تحديث تذكرة الدعم بنجاح!',
    'support_deleted' => 'تم حذف تذكرة الدعم بنجاح!',
    'status_updated' => 'تم تحديث حالة التذكرة بنجاح!',
    'ticket_not_found' => 'لم يتم العثور على التذكرة',
    'status_required' => 'يرجى اختيار حالة',
];
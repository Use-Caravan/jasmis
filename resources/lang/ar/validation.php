<?php

$validations = [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute may not be greater than :max.',
        'file' => ':attribute may not be greater than :max kilobytes.',
        'string' => ':attribute قد لا يكون أكبر من :max الشخصيات.',
        'array' => ':attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute يجب أن يكون رقما.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => ':attribute الحقل مطلوب.',
    'required_if' => ':attribute الحقل مطلوب عندما :other هو :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => ':attribute و :other يجب أن تتطابق.',
    //'same' => 'The :attribute and :other must match.',    
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => ':attribute لقد اتخذت بالفعل.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => ':attribute التنسيق غير صالح.',
    'uuid' => 'The :attribute must be a valid UUID.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'app_logo'  => [
            'logo_validate' => 'يجب ألا يكون الشعار فارغًا',
        ],
        'app_favicon'  => [
            'logo_validate' => 'يجب ألا يكون رمز Fav فارغًا',
        ],
        'redirect_url' => [
            'weburl' => ':attribute التنسيق غير صالح.'
        ],
        'end_time' => [
            'greater_than' => 'يجب أن يكون وقت النهاية أكبر من وقت البدء',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'item_name.*'       => 'اسم العنصر',
        'item_description.*' => 'وصف السلعة',
        'allergic_ingredient.*' => 'عنصر الحساسية',
        'deliveryboy_name.*' => 'اسم التسليم',        
        'category_name.*'   => 'اسم التصنيف',
        'cuisine_name.*'    => 'اسم المطبخ',
        'ingredient_name.*' => 'اسم العنصر',
        'ingredient_group_name.*' => 'اسم مجموعة المكونات',
        'main_category_id'  => 'الفئة الرئيسية',
        'is_main_category' => 'نوع الفئة',
        'vendor_id' => 'اسم البائع',
        'vendor_name.*' => 'اسم البائع',
        'vendor_description.*' => 'وصف البائع',
        'category_id' => 'اسم التصنيف',
        'cuisine_id' => 'اسم المطبخ',
        'country_id' => 'اسم الدولة',
        'city_id' => 'اسم المدينة',
        'area_id' => 'اسم المنطقة',
        'delivery_area_name.*' => 'اسم منطقة التسليم',
        'shopbeneficiary_id'    => 'متجر المستفيد',
        'userbeneficiary_id'    => 'مستخدم المستفيد',
        'title.*' => 'عنوان',
        'loyalty_level_name.*' => 'اسم مستوى الولاء',
        'from_point' => 'من النقطة',
        'to_point' => 'أن نشير',
        'from_km' => 'من الكيلومتر',
        'to_km' => 'الى كم',
        'price' => 'السعر',
        'keywords.*' => 'الكلمات الدالة',
        'description.*' => 'وصف',
        'cms_content.*' => 'يحتوى',
        'question.*' => 'سؤال',
        'answer.*' => 'إجابة',
        'banner_name.*' => 'اسم الشعار',
        'banner_file.*' => 'ملف بانر',        
        'address_type_name.*' => 'نوع العنوان',
        'country_name.*' => 'اسم الدولة',
        'area_name.*' => 'اسم المنطقة',
        'city_name.*' => 'اسم المنطقة',
        'latitude'  => 'خط العرض',
        'longitude' => 'خط الطول',
        'sort_no' => 'رقم الفرز',
        'minimum' => 'كمية الحد الأدنى',
        'maximum' => 'أقصى كمية',
        'ingredient_type' => 'نوع المكون',
        'item_price' => 'سعر البند',
        'ingredient_group_id' => 'سعر البند',        
        'preparation_time' => 'وقت التحضير',
        'delivery_time' => 'موعد التسليم',
        'pickup_time' => 'اختار المعاد',
        'tax' => 'ضريبة',
        'service_tax' => 'ضريبة الخدمة',
        'commission' => 'عمولة',
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'phone_number' => 'رقم الهاتف',
        'mobile_number' => 'رقم الهاتف المحمول',
        'contact_number' => 'رقم الاتصال',
        'min_order_value' => 'الحد الأدنى لقيمة النظام',  
        'password' => 'كلمه السر',      
        'confirm_password' => 'تأكيد كلمة المرور',
        'delivery_area_id'  => 'اسم منطقة التسليم',
        'first_name.*' => 'الاسم الاول',
        'role_name' => 'اسم الدور',
        'address.*' => 'عنوان',        
        'expiry_date' => 'تاريخ الانتهاء',
        'value' => 'القيمة',
        'max_redeem_amount' => 'الحد الأقصى لمبلغ الاسترداد',
        'app_type' => 'نوع التطبيق',
        'apply_promo_for' => 'تطبيق الترويجي',
        'promo_for_user' => 'الترويجي للمستخدم',
        'promo_for_shops' => 'الترويجي للمحلات التجارية',
        'newsletter_title' => 'عنوان النشرة الإخبارية',
        'newsletter_content ' => 'محتوى النشرة الإخبارية',
        'newsletter_subscribers' => 'المشتركين في النشرة الإخبارية',
        'newsletter_id' => 'النشرة الإخبارية',
        'app_name' => 'app name',
        'app_description' => 'وصف التطبيق',
        'app_meta_keywords' => 'كلمات ميتا تطبيقية',
        'app_meta_description' => 'وصف وصف التطبيق',
        'play_store_link' => 'رابط متجر اللعب',
        'app_store_link' => 'رابط متجر التطبيقات',
        'app_latitude' => 'خط العرض التطبيق',
        'app_longitude' => 'خط طول التطبيق',
        'map_key'       => 'مفتاح الخريطة',
        'app_primary_color' => 'لون التطبيق الأساسي',
        'app_contact_number' => 'رقم اتصال التطبيق',
        'app_email' => 'البريد الإلكتروني للتطبيق',
        'smtp_host' => 'مضيف بروتوكول نقل البريد البسيط',
        'smtp_password' => 'كلمة مرور بروتوكول نقل البريد البسيط',
        'smtp_username' => 'اسم مستخدم بروتوكول نقل البريد البسيط',
        'encryption' => 'التشفير',
        'post' => 'ميناء',
        'loyalty_amount' => 'مبلغ الولاء',
        'loyalty_point_for_amount' => 'كمية',
        'loyalty_points' => 'نقاط الولاء',
        'loyalty_amount_for_points' => 'نقاط',
        'social_twitter' => 'تغريد',
        'social_facebook' => 'فيس بوك',
        'social_instagram' => 'الانستقرام',
        'currency_code' => 'رمز العملة',
        'currency_symbol' => 'رمز العملة',
        'currency_position' => 'موقف العملة',
        'redirect_url' => 'إعادة توجيه URL'  ,
        'amount' => 'كمية',
        'points' => 'نقاط',
        'address_line_one' => 'سطر العنوان واحد',
        'address_type_id' => 'نوع العنوان',
        'company' => 'شركة',
        'address_line_two' => 'سطر العنوان الثاني',
        'landmark' => 'معلم معروف',
    ],
];

foreach(\App\Language::getList() as $key => $value) {
    $validations['attributes']["banner_file.$value->language_code"] = 'ملف بانر';
    $validations['attributes']["vendor_logo.$value->language_code"] = 'شعار البائع';
    $validations['attributes']["item_image.$value->language_code"] = 'صورة البند';
}
return $validations;
<?php

return[
    'forbidden' => 'غير مسموح لك بإنشاء مقال',
    'article' => [
        'added' => 'تمت إضافة المقال',
        'updated' => 'تم تحديث المقال',
        'deleted' => 'تم حذف المقال',
        'list' => 'قائمة المقالات',
        'by_category' => 'قائمة المقالات حسب الفئة',
        'details' => 'تفاصيل المقال',
        'search' => 'البحث عن المقال',
        'published' => 'تم النشر',
        'not_published' => 'جاري النشر',
        'rated' => 'تم التقييم',
    ],
    'book_parts' => [
        'added' => 'تمت إضافة الجزء',
        'updated' => 'تم تحديث الجزء',
        'deleted' => 'تم حذف الجزء',
        'list' => 'قائمة الأجزاء',
        'details' => 'تفاصيل الجزء',
        'search' => 'البحث عن الجزء',
        'array' => 'يجب أن تكون أجزاء الكتاب في شكل مصفوفة.',
        'chapter' => [
            'required' => 'حقل الفصل مطلوب.',
            'string' => 'يجب أن يكون الفصل نصًا.',
            'max' => 'يجب ألا يزيد الفصل عن 255 حرفًا.',
        ],
    ],
    'category' => [
        'added' => 'تمت إضافة الفئة',
        'updated' => 'تم تحديث الفئة',
        'deleted' => 'تم حذف الفئة',
        'list' => 'قائمة الفئات',
        'details' => 'تفاصيل الفئة',
        'search' => 'البحث عن الفئة',
    ],
    'user' => [
        'bad_credentials' => 'خطأ في تسجيل الدخول',
        'loggedIn' => 'تم تسجيل الدخول',
        'registered' => 'تم حفظ المستخدم',
        'logout' => 'تم تسجيل الخروج',
        'required_id' => "التعريف الخاص بالمستخدم مطلوب",
        'You cannot follow yourself' => 'لا يمكنك متابعة نفسك',
        'Already following' => 'تتبع بالفعل',
        'followed' => 'تمت المتابعة',
        'unfollowed' => 'تم إلغاء المتابعة',
        'follow' => 'متابعة',
        'unfollow' => 'إلغاء المتابعة',
    ],
    "chat_send" => "تم ارسال الرساله",
    'title' => [
        'required' => 'حقل العنوان مطلوب.',
        'string' => 'يجب أن يكون العنوان نصًا.',
        'max' => 'يجب ألا يزيد العنوان عن 255 حرفًا.',
    ],
    'description' => [
        'required' => 'حقل الوصف مطلوب.',
        'string' => 'يجب أن يكون الوصف نصًا.',
    ],
    'image' => [
        'required' => 'حقل الصورة مطلوب.',
        'image' => 'يجب أن يكون الملف المرفوع صورة.',
        'mimes' => 'يجب أن تكون الصورة من النوع: jpeg, png, jpg, gif.',
        'max' => 'يجب ألا يتجاوز حجم الصورة 2 ميجابايت.',
    ],
    'category_id' => [
        'required' => 'حقل التصنيف مطلوب.',
        'exists' => 'التصنيف المحدد غير موجود.',
    ],
    'book_page' => [
        'array' => 'يجب أن تكون الصفحات في شكل مصفوفة.',
        'page_number' => [
            'required' => 'حقل رقم الصفحة مطلوب.',
            'integer' => 'يجب أن يكون رقم الصفحة رقمًا صحيحًا.',
            'min' => 'يجب ألا يقل رقم الصفحة عن 1.',
        ],
        'content' => [
            'required' => 'حقل المحتوى مطلوب.',
            'string' => 'يجب أن يكون المحتوى نصًا.',
        ],
    ],
    'book_unFavourite' => 'تمت إزالة الكتاب من المفضلة',
    'book_favourite' => 'تمت إضافة الكتاب إلى المفضلة',


    'requested_amount.required' => 'المبلغ المطلوب مطلوب.',
    'requested_amount.numeric' => 'يجب أن يكون المبلغ المطلوب رقمًا.',
    'requested_amount.min' => 'يجب أن يكون المبلغ المطلوب على الأقل 0.',

    'payment_method.required' => 'طريقة الدفع مطلوبة.',
    'payment_method.string' => 'يجب أن تكون طريقة الدفع نصًا صالحًا.',
    'payment_method.in' => 'طريقة الدفع المحددة غير صالحة.',

    'phone.required_if' => 'رقم الهاتف مطلوب لفودافون، أورانج، واتصالات.',
    'phone.string' => 'يجب أن يكون رقم الهاتف نصًا صالحًا.',

    'beneficiary_name.required_if' => 'اسم المستفيد مطلوب للبنك.',
    'beneficiary_name.string' => 'يجب أن يكون اسم المستفيد نصًا صالحًا.',
    'beneficiary_name.max' => 'يجب ألا يزيد اسم المستفيد عن 255 حرفًا.',

    'bank_name.required_if' => 'اسم البنك مطلوب للبنك.',
    'bank_name.string' => 'يجب أن يكون اسم البنك نصًا صالحًا.',
    'bank_name.max' => 'يجب ألا يزيد اسم البنك عن 255 حرفًا.',

    'iban.required_if' => 'رقم IBAN مطلوب للبنك.',
    'iban.string' => 'يجب أن يكون رقم IBAN نصًا صالحًا.',
    'iban.regex' => 'تنسيق IBAN غير صالح.',

    'swift_bio_code.required_if' => 'كود SWIFT/BIC مطلوب للبنك.',
    'swift_bio_code.string' => 'يجب أن يكون كود SWIFT/BIC نصًا صالحًا.',
    'swift_bio_code.size' => 'يجب أن يكون كود SWIFT/BIC مكونًا من 8 أحرف بالضبط.',

    'beneficiary_address.string' => 'يجب أن يكون عنوان المستفيد نصًا صالحًا.',
    'beneficiary_address.max' => 'يجب ألا يزيد عنوان المستفيد عن 255 حرفًا.',

    'email_binance.required_if' => 'البريد الإلكتروني مطلوب لباينانس وPayoneer.',
    'email_binance.email' => 'يجب أن يكون البريد الإلكتروني لباينانس عنوان بريد إلكتروني صالحًا.',
    'email_binance.max' => 'يجب ألا يزيد البريد الإلكتروني لباينانس عن 255 حرفًا.',

    'wallet_id.required_if' => 'معرّف المحفظة مطلوب لـ InstaPay وPayeer وPerfect Money.',
    'wallet_id.string' => 'يجب أن يكون معرّف المحفظة نصًا صالحًا.',
    'wallet_id.max' => 'يجب ألا يزيد معرّف المحفظة عن 255 حرفًا.',

    'wallet_name.required_if' => 'اسم المحفظة مطلوب لـ InstaPay.',
    'wallet_name.string' => 'يجب أن يكون اسم المحفظة نصًا صالحًا.',
    'wallet_name.max' => 'يجب ألا يزيد اسم المحفظة عن 255 حرفًا.',

    'user_not_found' => 'المستخدم غير موجود.',

    'roles' => [
        'required' => 'دور غير صالح لهذا الإجراء.',
        'reader_only' => 'هذه الخطوة متاحة لدور القارئ فقط.',
    ],

    'invitation_code' => [
        'invalid' => 'كود الدعوة غير صالح.',
        'used' => 'تم استخدام كود الدعوة بالفعل.',
        'cannot_use_own' => 'لا يمكنك استخدام كود الدعوة الخاص بك.',
        'already_used_by_user' => 'لقد استخدمت هذا الكود من قبل.',
    ],

];

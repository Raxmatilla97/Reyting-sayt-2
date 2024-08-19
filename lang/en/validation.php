<?php

return [

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

    'accepted' => ':attribute maydoni qabul qilinishi kerak.',
    'accepted_if' => ':other :value bo‘lganda :attribute maydoni qabul qilinishi kerak.',
    'active_url' => ':attribute maydoni haqiqiy URL bo‘lishi kerak.',
    'after' => ':attribute maydoni :date sanasidan keyin bo‘lishi kerak.',
    'after_or_equal' => ':attribute maydoni :date sanasidan keyin yoki teng bo‘lishi kerak.',
    'alpha' => ':attribute maydoni faqat harflardan iborat bo‘lishi kerak.',
    'alpha_dash' => ':attribute maydoni faqat harflar, raqamlar, chiziqlar va pastki chiziqlardan iborat bo‘lishi kerak.',
    'alpha_num' => ':attribute maydoni faqat harflar va raqamlardan iborat bo‘lishi kerak.',
    'array' => ':attribute maydoni massiv bo‘lishi kerak.',
    'ascii' => ':attribute maydoni faqat bir baytli alfanumerik belgilar va simvollardan iborat bo‘lishi kerak.',
    'before' => ':attribute maydoni :date sanasidan oldin bo‘lishi kerak.',
    'before_or_equal' => ':attribute maydoni :date sanasidan oldin yoki teng bo‘lishi kerak.',
    'between' => [
        'array' => ':attribute maydoni :min va :max elementlar orasida bo‘lishi kerak.',
        'file' => ':attribute maydoni :min va :max kilobayt orasida bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :min va :max orasida bo‘lishi kerak.',
        'string' => ':attribute maydoni :min va :max belgilar orasida bo‘lishi kerak.',
    ],
    'boolean' => ':attribute maydoni haqiqat yoki yolg‘on bo‘lishi kerak.',
    'can' => ':attribute maydoni ruxsat etilmagan qiymatni o‘z ichiga oladi.',
    'confirmed' => ':attribute maydoni tasdiqlash mos kelmaydi.',
    'current_password' => 'Parol noto‘g‘ri.',
    'date' => ':attribute maydoni haqiqiy sana bo‘lishi kerak.',
    'date_equals' => ':attribute maydoni :date sanasi bilan teng bo‘lishi kerak.',
    'date_format' => ':attribute maydoni :format formatiga mos bo‘lishi kerak.',
    'decimal' => ':attribute maydoni :decimal kasr joylarini o‘z ichiga olishi kerak.',
    'declined' => ':attribute maydoni rad etilishi kerak.',
    'declined_if' => ':attribute maydoni :other :value bo‘lganda rad etilishi kerak.',
    'different' => ':attribute maydoni va :other turli bo‘lishi kerak.',
    'digits' => ':attribute maydoni :digits raqamdan iborat bo‘lishi kerak.',
    'digits_between' => ':attribute maydoni :min va :max raqamlar orasida bo‘lishi kerak.',
    'dimensions' => ':attribute maydonining rasm o‘lchamlari noto‘g‘ri.',
    'distinct' => ':attribute maydoni takroriy qiymatga ega.',
    'doesnt_end_with' => ':attribute maydoni quyidagi qiymatlarning birortasi bilan tugamasligi kerak: :values.',
    'doesnt_start_with' => ':attribute maydoni quyidagi qiymatlarning birortasi bilan boshlanmasligi kerak: :values.',
    'email' => ':attribute maydoni haqiqiy elektron pochta manzili bo‘lishi kerak.',
    'ends_with' => ':attribute maydoni quyidagi qiymatlarning birortasi bilan tugashi kerak: :values.',
    'enum' => 'Tanlangan :attribute noto‘g‘ri.',
    'exists' => 'Tanlangan :attribute noto‘g‘ri.',
    'extensions' => ':attribute maydoni quyidagi kengaytmalar bilan bo‘lishi kerak: :values.',
    'file' => ':attribute maydoni fayl bo‘lishi kerak.',
    'filled' => ':attribute maydoni qiymatga ega bo‘lishi kerak.',
    'gt' => [
        'array' => ':attribute maydoni :value elementdan ko‘proq bo‘lishi kerak.',
        'file' => ':attribute maydoni :value kilobaytdan katta bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :value dan katta bo‘lishi kerak.',
        'string' => ':attribute maydoni :value belgidan katta bo‘lishi kerak.',
    ],
    'gte' => [
        'array' => ':attribute maydoni :value yoki undan ko‘proq elementga ega bo‘lishi kerak.',
        'file' => ':attribute maydoni :value kilobaytdan katta yoki teng bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :value dan katta yoki teng bo‘lishi kerak.',
        'string' => ':attribute maydoni :value belgidan katta yoki teng bo‘lishi kerak.',
    ],
    'hex_color' => ':attribute maydoni haqiqiy oltita raqamli rang kodiga ega bo‘lishi kerak.',
    'image' => ':attribute maydoni rasm bo‘lishi kerak.',
    'in' => 'Tanlangan :attribute noto‘g‘ri.',
    'in_array' => ':attribute maydoni :other da mavjud bo‘lishi kerak.',
    'integer' => ':attribute maydoni butun son bo‘lishi kerak.',
    'ip' => ':attribute maydoni haqiqiy IP manzil bo‘lishi kerak.',
    'ipv4' => ':attribute maydoni haqiqiy IPv4 manzil bo‘lishi kerak.',
    'ipv6' => ':attribute maydoni haqiqiy IPv6 manzil bo‘lishi kerak.',
    'json' => ':attribute maydoni haqiqiy JSON qator bo‘lishi kerak.',
    'lowercase' => ':attribute maydoni kichik harflardan iborat bo‘lishi kerak.',
    'lt' => [
        'array' => ':attribute maydoni :value elementdan kam bo‘lishi kerak.',
        'file' => ':attribute maydoni :value kilobaytdan kichik bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :value dan kichik bo‘lishi kerak.',
        'string' => ':attribute maydoni :value belgidan kichik bo‘lishi kerak.',
    ],
    'lte' => [
        'array' => ':attribute maydoni :value elementdan ko‘p bo‘lmasligi kerak.',
        'file' => ':attribute maydoni :value kilobaytdan kichik yoki teng bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :value dan kichik yoki teng bo‘lishi kerak.',
        'string' => ':attribute maydoni :value belgidan kichik yoki teng bo‘lishi kerak.',
    ],
    'mac_address' => ':attribute maydoni haqiqiy MAC manzil bo‘lishi kerak.',
    'max' => [
        'array' => ':attribute maydoni :max elementdan ko‘p bo‘lmasligi kerak.',
        'file' => ':attribute maydoni :max kilobaytdan katta bo‘lmasligi kerak.',
        'numeric' => ':attribute maydoni :max dan katta bo‘lmasligi kerak.',
        'string' => ':attribute maydoni :max belgidan katta bo‘lmasligi kerak.',
    ],
    'max_digits' => ':attribute maydoni :max dan ortiq raqamga ega bo‘lmasligi kerak.',
    'mimes' => ':attribute maydoni quyidagi turdagi fayl bo‘lishi kerak: :values.',
    'mimetypes' => ':attribute maydoni quyidagi turdagi fayl bo‘lishi kerak: :values.',
    'min' => [
        'array' => ':attribute maydoni kamida :min elementga ega bo‘lishi kerak.',
        'file' => ':attribute maydoni kamida :min kilobayt bo‘lishi kerak.',
        'numeric' => ':attribute maydoni kamida :min bo‘lishi kerak.',
        'string' => ':attribute maydoni kamida :min belgidan iborat bo‘lishi kerak.',
    ],
    'min_digits' => ':attribute maydoni kamida :min ta raqamga ega bo‘lishi kerak.',
    'missing' => ':attribute maydoni mavjud bo‘lmasligi kerak.',
    'missing_if' => ':attribute maydoni :other :value bo‘lganda mavjud bo‘lmasligi kerak.',
    'missing_unless' => ':attribute maydoni :other :value bo‘lmagan taqdirda mavjud bo‘lmasligi kerak.',
    'missing_with' => ':attribute maydoni :values mavjud bo‘lganda mavjud bo‘lmasligi kerak.',
    'missing_with_all' => ':attribute maydoni :values mavjud bo‘lganda mavjud bo‘lmasligi kerak.',
    'multiple_of' => ':attribute maydoni :value ning ko‘paytmasi bo‘lishi kerak.',
    'not_in' => 'Tanlangan :attribute noto‘g‘ri.',
    'not_regex' => ':attribute maydoni formati noto‘g‘ri.',
    'numeric' => ':attribute maydoni raqam bo‘lishi kerak.',
    'password' => [
        'letters' => ':attribute maydoni kamida bitta harf o‘z ichiga olishi kerak.',
        'mixed' => ':attribute maydoni kamida bitta katta va bitta kichik harfni o‘z ichiga olishi kerak.',
        'numbers' => ':attribute maydoni kamida bitta raqam o‘z ichiga olishi kerak.',
        'symbols' => ':attribute maydoni kamida bitta simbolni o‘z ichiga olishi kerak.',
        'uncompromised' => 'Berilgan :attribute ma’lumot oqib ketishida ko‘rinib qolgan. Iltimos, boshqa :attribute tanlang.',
    ],
    'present' => ':attribute maydoni mavjud bo‘lishi kerak.',
    'present_if' => ':attribute maydoni :other :value bo‘lganda mavjud bo‘lishi kerak.',
    'present_unless' => ':attribute maydoni :other :value bo‘lmagan taqdirda mavjud bo‘lishi kerak.',
    'present_with' => ':attribute maydoni :values mavjud bo‘lganda mavjud bo‘lishi kerak.',
    'present_with_all' => ':attribute maydoni :values mavjud bo‘lganda mavjud bo‘lishi kerak.',
    'prohibited' => ':attribute maydoni man qilingan.',
    'prohibited_if' => ':attribute maydoni :other :value bo‘lganda man qilingan.',
    'prohibited_unless' => ':attribute maydoni :other :values da bo‘lmagan taqdirda man qilingan.',
    'prohibits' => ':attribute maydoni :other ning mavjud bo‘lishini man qiladi.',
    'regex' => ':attribute maydoni formati noto‘g‘ri.',
    'required' => ':attribute maydoni talab qilinadi.',
    'required_array_keys' => ':attribute maydoni :values uchun kiritishlarni o‘z ichiga olishi kerak.',
    'required_if' => ':attribute maydoni :other :value bo‘lganda talab qilinadi.',
    'required_if_accepted' => ':attribute maydoni :other qabul qilingan bo‘lganda talab qilinadi.',
    'required_unless' => ':attribute maydoni :other :values da bo‘lmagan taqdirda talab qilinadi.',
    'required_with' => ':attribute maydoni :values mavjud bo‘lganda talab qilinadi.',
    'required_with_all' => ':attribute maydoni :values mavjud bo‘lganda talab qilinadi.',
    'required_without' => ':attribute maydoni :values mavjud bo‘lmagan taqdirda talab qilinadi.',
    'required_without_all' => ':attribute maydoni :values ning hech biri mavjud bo‘lmagan taqdirda talab qilinadi.',
    'same' => ':attribute maydoni :other bilan mos bo‘lishi kerak.',
    'size' => [
        'array' => ':attribute maydoni :size elementlarni o‘z ichiga olishi kerak.',
        'file' => ':attribute maydoni :size kilobayt bo‘lishi kerak.',
        'numeric' => ':attribute maydoni :size bo‘lishi kerak.',
        'string' => ':attribute maydoni :size belgidan iborat bo‘lishi kerak.',
    ],
    'starts_with' => ':attribute maydoni quyidagi qiymatlarning birortasi bilan boshlanishi kerak: :values.',
    'string' => ':attribute maydoni qator bo‘lishi kerak.',
    'timezone' => ':attribute maydoni haqiqiy vaqt zonasi bo‘lishi kerak.',
    'unique' => ':attribute allaqachon band qilingan.',
    'uploaded' => ':attribute yuklab olinmadi.',
    'uppercase' => ':attribute maydoni katta harflardan iborat bo‘lishi kerak.',
    'url' => ':attribute maydoni haqiqiy URL bo‘lishi kerak.',
    'ulid' => ':attribute maydoni haqiqiy ULID bo‘lishi kerak.',
    'uuid' => ':attribute maydoni haqiqiy UUID bo‘lishi kerak.',

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

    'attributes' => [],

];

<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Validator;

class CategoryService
{
    public function validation($request)
    {
           return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:50|unique:categories,name',
                'slug' => 'required|string|max:50|unique:categories,slug',
            ],
            [
                'name.required' => 'وارد کردن نام دسته‌بندی الزامی است.',
                'name.string' => 'نام دسته‌بندی باید به صورت متن باشد.',
                'name.max' => 'نام دسته‌بندی نباید بیشتر از ۵۰ کاراکتر باشد.',
                'name.unique' => 'این نام دسته‌بندی قبلاً ثبت شده است.',

                'slug.required' => 'وارد کردن اسلاگ الزامی است.',
                'slug.string' => 'اسلاگ باید به صورت متن باشد.',
                'slug.max' => 'اسلاگ نباید بیشتر از ۵۰ کاراکتر باشد.',
                'slug.unique' => 'این اسلاگ قبلاً ثبت شده است.',
            ]
        );
    }
}

<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Validator;

class ProductService
{
    public function validation($request)
    {
        return Validator::make(
            $request->all(),
            [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|unique:products,name',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'thumbnail' => 'nullable|image|max:2048',
            ],
            [
                'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
                'category_id.exists' => 'دسته‌بندی انتخاب‌شده معتبر نیست.',

                'name.required' => 'نام محصول الزامی است.',
                'name.string' => 'نام محصول باید به صورت متن باشد.',
                'name.unique' => 'این نام محصول قبلاً ثبت شده است.',

                'description.string' => 'توضیحات باید به صورت متن باشد.',

                'price.required' => 'قیمت محصول الزامی است.',
                'price.numeric' => 'قیمت باید عدد باشد.',
                'price.min' => 'قیمت نمی‌تواند کمتر از صفر باشد.',

                'stock.required' => 'موجودی محصول الزامی است.',
                'stock.integer' => 'موجودی باید عدد صحیح باشد.',
                'stock.min' => 'موجودی نمی‌تواند منفی باشد.',

                'thumbnail.image' => 'فایل آپلود شده باید تصویر باشد.',
                'thumbnail.max' => 'حجم تصویر نباید بیشتر از ۲ مگابایت باشد.',
            ]
        );
    }
}

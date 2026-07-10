<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function validation($request)
    {
        return Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:150',
                'password' => 'required|string|max:50',
            ],
            [
                'email.required' => 'ایمیل الزامی است.',
                'email.email'    => 'فرمت ایمیل معتبر نیست.',
                'email.max'      => 'ایمیل نباید بیشتر از ۱۵۰ کاراکتر باشد.',

                'password.required' => 'رمز عبور الزامی است.',
                'password.string'   => 'رمز عبور باید به صورت متن باشد.',
                'password.max'      => 'رمز عبور نباید بیشتر از ۵۰ کاراکتر باشد.',
            ]
        );
    }

    public function regiValidation($request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:50',
                'email' => 'required|email|max:150',
                'password' => 'required|string|max:50',
                'is_admin' => 'required|boolean',
            ],
            [
                'name.required' => 'نام الزامی است.',
                'name.string'   => 'نام باید به صورت متن باشد.',
                'name.max'      => 'نام نباید بیشتر از ۵۰ کاراکتر باشد.',

                'email.required' => 'ایمیل الزامی است.',
                'email.email'    => 'فرمت ایمیل معتبر نیست.',
                'email.max'      => 'ایمیل نباید بیشتر از ۱۵۰ کاراکتر باشد.',

                'password.required' => 'رمز عبور الزامی است.',
                'password.string'   => 'رمز عبور باید به صورت متن باشد.',
                'password.max'      => 'رمز عبور نباید بیشتر از ۵۰ کاراکتر باشد.',

                'is_admin.required' => 'وضعیت مدیر بودن الزامی است.',
                'is_admin.boolean'  => 'مقدار مدیر بودن باید درست یا غلط باشد.',
            ]
        );
    }
}

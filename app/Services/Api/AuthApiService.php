<?php

namespace App\Services\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthApiService
{
    public function validation(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|email|max:150|unique:users,email',
                'password' => 'required|string|max:50',
                'is_admin' => 'required|boolean',
            ],
            [
                'name.required' => 'نام الزامی است.',
                'name.string' => 'نام باید به صورت متن باشد.',
                'name.min' => 'نام باید حداقل ۳ کاراکتر باشد.',
                'name.max' => 'نام نباید بیشتر از ۵۰ کاراکتر باشد.',

                'email.required' => 'ایمیل الزامی است.',
                'email.email' => 'فرمت ایمیل معتبر نیست.',
                'email.max' => 'ایمیل نباید بیشتر از ۱۵۰ کاراکتر باشد.',
                'email.unique' => 'این ایمیل قبلاً ثبت شده است.',

                'password.required' => 'رمز عبور الزامی است.',
                'password.string' => 'رمز عبور باید به صورت متن باشد.',
                'password.max' => 'رمز عبور نباید بیشتر از ۵۰ کاراکتر باشد.',

                'is_admin.required' => 'وضعیت مدیر بودن الزامی است.',
                'is_admin.boolean' => 'وضعیت مدیر بودن باید درست یا غلط باشد.',
            ]
        );
    }

    public static function validationLogin(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:150',
                'password' => 'required|string|max:50',
            ],
            [
                'email.required' => 'ایمیل الزامی است.',
                'email.email' => 'فرمت ایمیل معتبر نیست.',
                'email.max' => 'ایمیل نباید بیشتر از ۱۵۰ کاراکتر باشد.',

                'password.required' => 'رمز عبور الزامی است.',
                'password.string' => 'رمز عبور باید به صورت متن باشد.',
                'password.max' => 'رمز عبور نباید بیشتر از ۵۰ کاراکتر باشد.',
            ]
        );
    }

}

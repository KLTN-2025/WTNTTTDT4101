<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'role' => 'required|in:admin,teacher,student,guest',
            'status' => 'nullable|in:active,disabled',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'role.required' => 'Vai trò là bắt buộc',
            'role.in' => 'Vai trò không hợp lệ',
            'status.in' => 'Trạng thái không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->header('HX-Request')) {
            throw new HttpResponseException(
                response()->view('admin.users.partials.user-form', [
                    'errors' => $validator->errors()
                ])->withErrors($validator)->withInput(), 422
            );
        }

        parent::failedValidation($validator);
    }
}

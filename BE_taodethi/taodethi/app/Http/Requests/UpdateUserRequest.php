<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules()
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => 'required|in:admin,teacher,student,guest',
            'status' => 'required|in:active,disabled',
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
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->header('HX-Request')) {
            $user = $this->route('user');
            throw new HttpResponseException(
                response()->view('admin.users.partials.user-form', [
                    'user' => $user
                ])->withErrors($validator)->withInput(), 422
            );
        }

        parent::failedValidation($validator);
    }
}

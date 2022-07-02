<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge(['user_id' => isUser() ? auth()->id() : $this->user->id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:3|max:50',
            'email' => 'unique:users,email,' . $this->user_id,
            'phone' => 'string',
            'password' => 'string|min:6|max:50',
            'is_active' => isAdmin() ? 'boolean' : 'exclude',
            'photo' => 'mimes:jpg,jpeg,png|exclude',
            'nib' => 'string|nullable',
            'dob' => 'date|nullable',
            'address' => 'string'
        ];
    }


    public function validated(): array
    {
        $updates = [];

        if ($this->has('password')) {
            array_merge($updates, ['password' => Hash::make($this->password)]);
        }

        return array_merge(parent::validated(), $updates);
    }
}

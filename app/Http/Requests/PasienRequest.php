<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
            case 'POST':
                return [
                    'nama' => 'required|min:1|max:255|unique:users,name',
                    'alamat' => 'required|min:1|max:255',
                    'no_ktp' => 'required|min:16|max:16|unique:pasiens,no_ktp',
                    'no_hp' => 'required|min:1|max:12',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'nama' => 'required|min:1|max:255|unique:users,name',
                    'alamat' => 'required|min:1|max:255',
                    'no_ktp' => 'required|min:16|max:16|unique:pasiens,no_ktp,' . request()->id,
                    'no_hp' => 'required|min:1|max:12',
                ];
            default:
                break;
        }
    }
}

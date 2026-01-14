<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Profil (table profil)
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date'],
            'adresse' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postale' => ['required', 'regex:/^\d{5}$/'],
            'commune' => ['nullable', 'string', 'max:100'],
            'num_tel' => ['nullable', 'regex:/^\d{10}$/'],
            'num_fixe' => ['nullable', 'regex:/^\d{10}$/'],
        ];
    }
}

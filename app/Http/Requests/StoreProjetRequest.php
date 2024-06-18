<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProjetRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nomprojet' => 'required|string|max:255',
            'typeprojet' => 'required|string|max:20',
            'description' => 'required|string|max:255',
            'nomclient' => 'required|string|max:255',
            'telephone' => 'required|string|regex:/^\+?[0-9\s\-]+$/|max:20',
            'email' => 'required|string|email|max:255|unique:projets,email'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nomprojet.required' => 'Le nom est obligatoire.',
            'typeprojet.required' => 'vueillez preciser le type de projet.',
            'description.required' => 'Description du projet est obligatoire.',
            'nomclient.required' => 'Le nom du client est obligatoire.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'telephone.regex' => 'Le téléphone doit être un numéro valide.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
           
            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422)
        );
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreEmployeeRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|regex:/^\+?[0-9\s\-]+$/|max:20',
            'email' => 'required|string|email|max:255|unique:employees,email',
            'profile' => 'required',
            'selectedSecteursIds' => 'required', // Règle pour valider les secteurs
            'selectedSecteursIds.*' => 'exists:secteurs,id', // Vérifie que tous les secteurs sélectionnés existent
            'selectedCompetencesIds' => 'required', // Règle pour valider les compétences
            'selectedCompetencesIds.*' => 'exists:competences,id', // Vérifie que toutes les compétences sélectionnées existent
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
            'nom.required' => 'Le nom est obligatoire.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'telephone.regex' => 'Le téléphone doit être un numéro valide.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'profile.required' => 'Le profile est obligatoire.',
            'selectedSecteursIds.required' => 'Les secteurs sont obligatoires.',
            'selectedSecteursIds' => 'Les secteurs doivent être sous forme de tableau.',
            'selectedSecteursIds.*.exists' => 'Un des secteurs sélectionnés n\'existe pas.',
            'selectedCompetencesIds.required' => 'Les compétences sont obligatoires.',
            'selectedCompetencesIds' => 'Les compétences doivent être sous forme de tableau.',
            'selectedCompetencesIds.*.exists' => 'Une des compétences sélectionnées n\'existe pas.',
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

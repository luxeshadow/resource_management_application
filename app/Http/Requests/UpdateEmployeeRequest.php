<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeRequest extends FormRequest
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
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:employees,email,' . $this->route('employee')->id,
           'selectedSecteursIds.*' => 'required|exists:sectors,id',
           'selectedCompetencesIds.*' => 'required|exists:competences,id',
           'selectedCompetencesIds' => 'required',

        ];
    }

    /* Get the error messages for the defined validation rules.
    *
    * @return array
    */
   public function messages()
   {
       return [
        'nom.required' => 'Le nom complet est obligatoire.',
        'telephone.required' => 'Le numéro de téléphone est obligatoire.',
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'email.unique' => 'Cet email est déjà utilisé.',
        'selectedSecteursIds.required' => 'Les secteurs sont obligatoires.',
        'selectedCompetencesIds.required' => 'Les compétences sont obligatoires.',
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

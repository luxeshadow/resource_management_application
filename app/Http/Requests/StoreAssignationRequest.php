<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreAssignationRequest extends FormRequest
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
           
            //'date' => 'required|date',
           
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
           
           //'date.required' => 'Description du projet est obligatoire.',
           
           
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

<?php

namespace App\Http\Requests;

use App\Rules\NotGmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

class PatientStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true; # Valida que el usuario tenga permiso de hacer esta solicitud
    }

    public function rules(): array {
        return [
            "name" => "required",
            "email" => [Rule::unique("patients")->ignore($this->route()->parameter("patientId")), "email", "unique:patients,email", new NotGmail()],
            "number" => "required",
            "prefix" => "required",
            "docImg" => "required|image|mimes:jpeg,png,jpg,gif,svg"
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\Patients\StorePatient;
use App\Http\Requests\PatientStoreRequest;
use App\Models\Patient;
use App\Models\Prefix;
use Exception;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller {
    public function index(): JsonResponse {
        return response()->json(["status" => "OK", "errMsg" => "", "patients" => Patient::all()]);
    }

    public function store(PatientStoreRequest $request, ?Patient $patient = null): JsonResponse {
        try {
            (new StorePatient())->execute($request->validated(), $patient);
        } catch (Exception) {
            # Retorno un mensaje y estado de error.
            return response()->json(["status" => "KO", "errMsg" => "There was an error while storing patient data. Please contact support.", "patient" => null], 401);
        }

        # Redirección con un estado OK.
        return response()->json(["status" => "OK", "errMsg" => "", "patient" => Patient::all()->last()], 201);
    }

    public function prefixList(): JsonResponse {
        return response()->json(["status" => "OK", "errMsg" => "", "prefix" => Prefix::all()]);
    }
}

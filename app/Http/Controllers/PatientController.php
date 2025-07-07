<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prefix;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller {
    public function index(): JsonResponse {
        return response()->json(["status" => "OK", "errMsg" => "", "patients" => Patient::all()]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse {
        # Valido los campos del formulario.
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:patients,email",
            "number" => "required",
            "prefix" => "required",
            "docImg" => "required"
        ]);

        # Valido que el mail sea gmail
        if (explode("@", $request->get("email"))[1] !== "gmail.com") {
            throw ValidationException::withMessages([
                "email" => "Invalid email address"
            ]);
        }

        try {
            # Guardo la imagen el storage y recupero la ruta final para guardarla en el registro del usuario.
            $docImgPath = Patient::storeDocImg($request->allFiles()["docImg"]);

            # Guarda el registro en la BDD.
            Patient::createPatient(
                patientname: $request->get("name"),
                email: $request->get("email"),
                phoneNumber: $request->get("prefix") . $request->get("number"),
                documentFilePath: $docImgPath
            );

            # Envía el mail de confirmación al usuario.
            Patient::sendConfirmationToPatient(email: $request->get("email"));
        } catch (Exception $ex) {
            # Retorno un mensaje y estado de error.
            return response()->json(["status" => "KO", "errMsg" => "There was an error while storing patient data. Please contact support.", "patient" => null], 401);
        }

        # Redirección con un estado OK.
        return response()->json(["status" => "OK", "errMsg" => "", "patient" => Patient::getLastPatient()], 201);
    }

    public function prefixList(): JsonResponse {
        return response()->json(["status" => "OK", "errMsg" => "", "prefix" => Prefix::all()]);
    }
}

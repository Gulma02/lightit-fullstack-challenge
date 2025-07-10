<?php

namespace App\Actions\Patients;

use App\Models\Patient;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StorePatient {
    /**
     * @throws Exception
     */
    public function execute(array $data, ?Patient $patient = null): void {
        if (is_null($patient)) {
            $patient = new Patient();
        }

        try {
            $imagePath = $patient->documentFilePath;
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                // Si hay una nueva imagen, elimino la antigua si existe y la guardo
                if ($patient && $patient->documentFilePath) {
                    Storage::disk('public')->delete($patient->documentFilePath);
                }

                $imagePath = Patient::storeDocImg($data['image']);
            }

            Patient::updateOrCreate(
                ["id" => $patient->id],
                [
                    "name" => $data['name'],
                    "email" => $data['email'],
                    "number" => $data['number'],
                    "documentFilePath" => $imagePath,
                ]
            );

            # Envía el mail de confirmación al usuario.
            #Patient::sendConfirmationToPatient(email: $request->get("email"));
        } catch (QueryException $ex) {
            Log::error("Failed to create patient - " . $ex->getMessage());
            throw new Exception();
        } catch (Exception $ex) {
            Log::error("Unexpected Error: Failed to create patient - " . $ex->getMessage());
            throw new Exception();
        }
    }
}

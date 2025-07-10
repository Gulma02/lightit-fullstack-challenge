<?php

namespace App\Models;

use App\Mail\ConfirmationEmail;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Patient extends Model {
    const storagePath = "app/public/patients/";
    public string $name = "";
    public string $email = "";
    public string $number = "";
    public ?string $documentFilePath = "";

    protected $fillable = [ 'name', 'email', 'number', "documentFilePath" ];

    /**
     * @throws Exception
     */
    public static function sendConfirmationToPatient(string $email): void {
        try {
            try {
                Mail::to($email)->send(new ConfirmationEmail());
            } catch (Exception $e) {
                Log::error("Sending email failed: " . $e->getMessage());
                throw new Exception("Error sending email");
            }

            # TODO: Agregar envío de SMS al usuario.
        } catch (Exception $ex) {
            Log::error("Patient - Send mail: " . $ex->getMessage());
            throw new Exception("There was an error while sending the confirmation email.");
        }
    }

    /**
     * @throws Exception
     */
    public static function storeDocImg($requestFile): string {
        try {
            $imgPath = Patient::saveFile($requestFile);
        } catch (Exception) {
            throw new Exception("Error saving file.");
        }

        return $imgPath;
    }

    /**
     * @throws Exception
     */
    public static function saveFile($requestFile): string {
        # Valido que exista el directorio dónde voy a guardar el archivo temporal
        if (!is_dir(storage_path(Patient::storagePath))) {
            mkdir(storage_path(Patient::storagePath), 0777, true);
        }

        try {
            # Guardo el archivo en el path de storage
            copy($requestFile->getPathname(), storage_path(Patient::storagePath) . $requestFile->getClientOriginalName());
        } catch (Exception $ex) {
            Log::error("Patient - Store file: " . $ex->getMessage());
            throw new Exception("There was an error while saving the file.");
        }

        # Retorno la ruta del archivo.
        return Patient::storagePath . $requestFile->getClientOriginalName();
    }
}

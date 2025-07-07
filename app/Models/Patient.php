<?php

namespace App\Models;

use App\Mail\ConfirmationEmail;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class Patient extends Model {
    const storagePath = "app/public/patients/";
    protected $fillable = [ 'name', 'email', 'number', "documentFilePath" ];


    /**
     * @throws Exception
     */
    public static function createPatient(string $patientname, string $email, string $phoneNumber, string $documentFilePath): void {
        try {
            Patient::create([
                "name" => $patientname,
                "email" => $email,
                "number" => $phoneNumber,
                "documentFilePath" => $documentFilePath,
            ]);
        } catch (QueryException $ex) {
            Log::error("Patient - Store: " . $ex->getMessage());
            throw new Exception("Error creating Patient.");
        }
    }

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

    public static function getLastPatient() {
        return Patient::all()->last();
    }

    /**
     * @throws Exception
     */
    public static function storeDocImg($requestFile): string {
        if (!Patient::validateFileExtension($requestFile)) {
            Log::error("Patient store file - Invalid file extension.");
            throw new Exception("File extension not allowed.");
        }

        try {
            $imgPath = Patient::saveFile($requestFile);
        } catch (Exception) {
            throw new Exception("Error saving file.");
        }

        return $imgPath;
    }

    public static function validateFileExtension($requestFile): bool {
        $acceptedExtensions = ['image/jpg', 'image/jpeg', 'image/png'];
        $fileMimeType = $requestFile->getClientMimeType();

        return in_array($fileMimeType, $acceptedExtensions);
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

    public static function buildMailBody(string $toEmail): array {
        return [
            "from" => [
                "email" => env('MAIL_FROM_ADDRESS'),
                "name" => env("MAIL_FROM_NAME")
            ],
            "to" => ["email" => $toEmail],
            "subject" => "Confirmation Email",
            "text" => "This is a confirmation email.",
            "category" => "Integration Test"
        ];
    }
}

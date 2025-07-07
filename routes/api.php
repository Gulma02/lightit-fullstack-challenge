<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "user"], function () {
    Route::get("", [PatientController::class, "list"])->name("user.list");
    Route::post("", [PatientController::class, "store"])->name("user.store");
})->middleware(["auth:sanctum"]);

Route::get("prefix", [PatientController::class, "prefixList"])->name("prefix.list");

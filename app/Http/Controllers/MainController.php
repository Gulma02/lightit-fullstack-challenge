<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Inertia\Inertia;
use Inertia\Response;

class MainController extends Controller {
    public function index(): Response{
        return Inertia::render("Index", ["patients" => Patient::all()]);
    }
}

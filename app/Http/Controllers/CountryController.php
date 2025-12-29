<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RegistrationCountry;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = RegistrationCountry::all();

        return response()->json([
            'status' => true,
            'message' => 'Countries retrieved successfully',
            'data' => $countries,
        ]);
    }
}

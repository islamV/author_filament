<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    public function index(): JsonResponse
    {
        $sections = Section::all();

        return response()->json([
            'status' => true,
            'message' => 'تم استرجاع الأقسام بنجاح', 
            'data' => $sections,
        ], 200);
    }
}

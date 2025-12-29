<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Author\User\AuthorResource;
use App\Http\Resources\v1\Author\User\AuthorListResource;

class AuthorController extends Controller
{
    public function getAuthors(): JsonResponse
    {
        $authors = User::whereHas('role', function($q) {
            $q->where('name', 'Author');
        })
        ->where('status', 'active')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'تم استرجاع المؤلفين بنجاح',
            'data' => AuthorResource::collection($authors),
        ]);
    }

    public function getVerifiedAuthors(): JsonResponse
    {
        $verifiedAuthors = User::whereHas('role', function($q) {
            $q->where('name', 'Verified Author');
        })
        ->where('status', 'active')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'تم استرجاع المؤلفين الموثقين بنجاح',
            'data' => AuthorResource::collection($verifiedAuthors),
        ]);
    }
}

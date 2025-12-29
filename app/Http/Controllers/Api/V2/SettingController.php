<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\SettingResource;

class SettingController extends Controller
{
    /**
     * Get all settings
     */
    public function getSettings()
    {
        $setting = Setting::first();

        if (! $setting) {
            return response()->json([
                'status' => false,
                'message' => 'Settings not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Settings fetched successfully',
            'data' => new SettingResource($setting),
        ]);
    }


    public function index(): JsonResponse
    {
        $setting = Setting::with(['banners' => fn($query) => $query->orderBy('order')])->first();

        $banners = $setting?->banners->map(fn($banner) => [
            'id' => $banner->id,
            'image' => asset('storage/' . $banner->image),
        ]) ?? [];

        return response()->json([
            'success' => true,
            'message' => 'تم استرجاع البنرات بنجاح',
            'data' => [
                'banners' => $banners,
            ],
        ]);
    }


    public function getPublishPolicy(): JsonResponse
    {
        $setting = Setting::first(); 

        $publishPolicy = $setting?->publish_policy ?? null;

        return response()->json([
            'success' => true,
            'message' => 'تم استرجاع سياسة النشر بنجاح',
            'data' => [
                'publishPolicy' => $publishPolicy,
            ],
        ]);
    }

}

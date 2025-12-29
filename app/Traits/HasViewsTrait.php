<?php

namespace App\Traits;

use App\Models\views;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;

trait HasViewsTrait
{
    public function views()
    {
       return $this->morphMany(Views::class, 'viewable');
    }

   

public function addView()
{
    $ip = request()->ip();
    $userAgent = request()->userAgent();
    $user = auth()->user();
    $userId = auth()->id();

    $existingView = $this->views()
        ->where('ip_address', $ip)
        ->where('user_agent', $userAgent)
        ->where('user_id', $userId)
        ->where('created_at', '>=', now()->subHours(5))
        ->first();

    // IP حقيقي للاختبار
    $testIp = '41.223.60.1'; // Egypt

    $response = Http::get("http://ip-api.com/json/{$testIp}");

    $countryName = $response->successful()
        && ($response->json()['status'] ?? null) === 'success'
            ? $response->json()['country']
            : null;

    

    if (!$existingView) {
        return $this->views()->create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'user_id' => $userId,
            'country' => $countryName,
        ]);
    }
}

    public function viewsCount($startDate = null, $endDate = null)
    {
        $query = $this->views();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->count();
    }

    public function uniqueViewCount($startDate = null, $endDate = null)
    {
        $query = $this->views();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->distinct('ip_address')->count('ip_address');
    }
    public function totalViews($startDate = null, $endDate = null)
    {
        return $this->books->sum(function ($book) use ($startDate, $endDate) {
            return $book->viewsCount($startDate, $endDate);
        });
    }
    public function dailyTotalViews($startDate, $endDate): array
    {
        $dailyViews = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $totalViews = $this->books->sum(function ($book) use ($dayStart, $dayEnd) {
                return $book->viewsCount($dayStart, $dayEnd);
            });

            $dailyViews[$date->toDateString()] = $totalViews;
        }

        return $dailyViews;
    }

    public function uniqueViews($startDate = null, $endDate = null)
    {
        $uniqueIps = collect();

        $this->books->each(function ($book) use ($uniqueIps, $startDate, $endDate) {
            $views = $book->views();

            if ($startDate && $endDate) {
                $views->whereBetween('created_at', [$startDate, $endDate]);
            }

            $uniqueIps = $uniqueIps->merge($views->get()->pluck('ip_address')->unique());
        });

        return $uniqueIps->unique()->count();
    }

}

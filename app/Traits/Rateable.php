<?php

namespace App\Traits;

use App\Models\Rating;

trait Rateable
{
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }

    public function rate($score , $comment)
    {
        return $this->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $score , 'comment' => $comment],
        );
    }

    public function getUserRating($userId)
    {
        return $this->ratings()
            ->where('user_id', $userId)
            ->value('rating');
    }
}

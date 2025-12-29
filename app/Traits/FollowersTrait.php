<?php

namespace App\Traits;

use App\Models\follows;
use App\Models\User;

trait FollowersTrait
{
    use ResponseTrait;

    public function follows()
    {
        return $this->hasMany(follows::class, 'follower_id');
    }

    public function followers()
    {
        return $this->hasMany(follows::class, 'following_id');
    }

    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }

    public function followedBy()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        );
    }

    public function follow(User $user)
    {
        if ($this->id === $user->id) {
            return $this->returnError('You cannot follow yourself', 400);
        }

        if ($this->isFollowing($user)) {
            return $this->returnError('Already following', 400);
        }

        return $this->following()->attach($user->id);
    }

    public function unfollow(User $user)
    {
        return $this->following()->detach($user->id);
    }

    public function toggleFollow(User $user)
    {
        if ($this->isFollowing($user)) {
            return $this->unfollow($user);
        }

        return $this->follow($user);
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user)
    {
        return $this->followedBy()->where('follower_id', $user->id)->exists();
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }

    public function followingCount()
    {
        return $this->follows()->count();
    }

}

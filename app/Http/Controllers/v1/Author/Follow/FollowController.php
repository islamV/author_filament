<?php

namespace App\Http\Controllers\v1\Author\Follow;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Author\Auth\AuthorFollowerResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    use ResponseTrait;
    public function toggleFollow(User $user)
    {
        auth()->user()->toggleFollow($user);

        $action = auth()->user()->isFollowing($user) ? 'follow' : 'unfollow';
        return $this->success("{$action}", 200);
    }

    public function followers(Request $request)
    {
        $user = Auth::user();
        $followers = $user->followedBy()->paginate($request->get('per_page', 20));

        return $this->returnData('Followers', 200,
            AuthorFollowerResource::collection($followers)
        );
    }

    public function following(User $user , Request $request)
    {
        $following = $user->following()->paginate($request->get('per_page', 20));

        return $this->returnData('Following', 200, $following);
    }

}

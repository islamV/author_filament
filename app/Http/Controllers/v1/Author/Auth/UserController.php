<?php

namespace App\Http\Controllers\v1\Author\Auth;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\v1\Author\Auth\AuthService;
use App\Services\v1\Author\Auth\UserService;
use App\Http\Requests\v1\Author\Auth\LoginRequest;
use App\Http\Requests\v1\Author\Auth\RegisterRequest;
use App\Http\Resources\v1\Author\Book\FavBookResource;
use App\Http\Resources\v1\Author\User\ProfileResource;
use App\Http\Resources\v1\Author\Auth\RegisterResource;
use App\Http\Requests\v1\Author\Auth\RegisterAuthorRequest;
use App\Http\Resources\v1\Author\User\AuthorProfileResource;
use App\Http\Resources\v1\Author\User\AuthorReaderPageResource;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function favorite(Book $book)
    {
        return $this->userService->favorite($book);
    }
    public function getFavouriteBooks(Request $request)
    {
        $book = FavBookResource::collection($this->userService->getFavouriteBooks($request->get("per_page",10)));
        return $this->returnData(__("messages.favourite_books"),200,$book);
    }

    public function getUserProfile()
    {
        return $this->returnData(__("messages.user_profile"),200,new ProfileResource($this->userService->getUserProfile()));
    }
    public function getAuthorProfile()
    {
        return $this->returnData(__("messages.Author_profile"),200,new AuthorProfileResource($this->userService->getAuthorProfile()));
    }
    public function getViews(Request $request)
    {
        return $this->returnData(__('messages.get.views'),200,
            ($this->userService->getViews($request)));
    }

    public function updateUserProfile(Request $request)
    {
        return $this->success(__('messages.update.profile'),200,
            $this->userService->updateUserProfile($request));
    }

    public function AuthorPage(User $user)
    {
        return $this->returnData(__('messages.Author_profile'),200,new AuthorReaderPageResource($this->userService->AuthorPage($user)));
    }

    public function convertToReader(): JsonResponse
    {
        $user = Auth::user();

        if ($user->role_id != 3) {
            return response()->json([
                'message' => 'يمكن فقط للمؤلفين التحويل إلى قارئ.'
            ], 403);
        }

        $user->role_id = 4;
        $user->save();

        return response()->json([
            'message' => 'تم تغيير دورك إلى قارئ.',
            'role' => $user->role->name
        ]);
    }

}

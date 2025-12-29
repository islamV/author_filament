<?php

namespace App\Services\v1\Author\Auth;

use App\Models\Book;
use App\Models\Favourite;
use App\Models\User;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use ResponseTrait, ImageTrait;
    public function favorite($model)
    {
        $user = Auth::user();
        if ($user->hasFavorited($model)) {
            $user->unfavourite($model);
            return  $this->success(__("messages.book_unFavourite"),200);
        } else {
            $user->favourite($model);
            return  $this->success(__("messages.book_favourite"),200);
        }
    }
    public function getFavouriteBooks($limit)
    {
        $user = auth()->user();
        return $user->favorites()
            ->where('favoritable_type', Book::class)
            ->with(['favoritable.user'])
            ->paginate($limit);
    }

    public function getUserProfile()
    {
        return Auth::user();
    }
    public function getAuthorProfile()
    {
        return Auth::user();
    }
    public function getViews($request)
    {
        $author = Auth::user();

        $settings = \App\Models\Setting::first();
        $moneyUses = $settings?->invitation_money_uses ?? 1;
        $rewardMoney = $settings?->invitation_reward_money ?? 0;

        $time = $request->input('time');
        $startDate = null;
        $endDate = null;

        if ($time == "today") {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } else if ($time == "last_7_days") {
            $startDate = now()->subDays(7)->startOfDay();
            $endDate = now()->endOfDay();
        } else if ($time == "this_month") {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } else if ($time == "last_month") {
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
        } else if ($time == "custom") {
            $date = Carbon::parse($request->input('day'));
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();
        }

        $periodViews = $author->totalViews($startDate, $endDate);
        $periodViewRevenue = $author->calculateEarnings($startDate, $endDate);

        $invitationUsesCount = \App\Models\InvitationCode::where('user_id', $author->id)
            ->whereNotNull('used_by')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->count();

        $invitationRevenue = floor($invitationUsesCount / $moneyUses) * $rewardMoney;

        $totalInvitationUses = \App\Models\InvitationCode::where('user_id', $author->id)
            ->whereNotNull('used_by')
            ->count();

        $totalAuthorRevenue = $author->calculateEarnings() +
                            floor($totalInvitationUses / $moneyUses) * $rewardMoney;
                            
        return [
            "total_author_revenue" => $totalAuthorRevenue,
            "invitation_count" => $invitationUsesCount,
            "invitation_revenue" => $invitationRevenue,
            "period_views" => $periodViews,
            "total_views" => $author->totalViews(),
            "period_view_revenue" => $periodViewRevenue,
        ];
    }








    public function updateUserProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'work_link' => 'nullable|string|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . Auth::id(),
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $image = $request->file('image')?->store('users', 'public');

        $user = Auth::user();
        $user->update([
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'work_link' => $request->work_link ?? $user->work_link,
            'phone' => $request->phone ?? $user->phone,
            'email' => $request->email ?? $user->email,
            'image' => $image ?? $user->image,
        ]);

        return $this->success(__('messages.update.profile'), 200);
    }


    public function AuthorPage(User $user)
    {
        $latest_books = $user->books()->where('is_published', 1)
            ->addSelect([
                'is_favorited' => Favourite::selectRaw('COUNT(*)')
                    ->whereColumn('favoritable_id', 'books.id')
                    ->where('favoritable_type', Book::class)
                    ->where('user_id', Auth::id())
            ])
            ->paginate(4);
        return [
            'user' => $user,
            'latest_books' => $latest_books
        ];
    }
}

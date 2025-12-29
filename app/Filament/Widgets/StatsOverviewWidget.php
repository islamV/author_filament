<?php

namespace App\Filament\Widgets;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 12;
    }

    protected function getStats(): array
    {
        $topAuthor = User::withCount('books')->orderByDesc('books_count')->first();
        $topPoints = User::orderByDesc('invitation_points')->first();
        $topMoney = User::all()->sortByDesc(fn($user) => $user->calculateEarnings())->first();
        $topBook = Book::all()->sortByDesc(fn($book) => $book->viewsCount())->first();
        $topAdmin = Admin::withCount('reviewedBooks')->orderByDesc('reviewed_books_count')->first();

        $topAuthorName = $topAuthor ? $topAuthor->name : 'لا يوجد';
        $topAuthorBooks = $topAuthor ? $topAuthor->books_count : 0;

        $topPointsName = $topPoints ? $topPoints->name : 'لا يوجد';
        $topPointsValue = $topPoints ? $topPoints->invitation_points : 0;

        $topMoneyName = $topMoney ? $topMoney->name : 'لا يوجد';
        $topMoneyValue = $topMoney ? $topMoney->calculateEarnings() : 0;

        $topBookTitle = $topBook ? $topBook->title : 'لا يوجد';
        $topBookViews = $topBook ? $topBook->viewsCount() : 0;

        $topAdminName = $topAdmin ? $topAdmin->name : 'لا يوجد';
        $topAdminReviews = $topAdmin ? $topAdmin->reviewed_books_count : 0;

        return [
            Stat::make(__('filament.widgets.total_users'), User::count())
                ->description(__('filament.widgets.total_users_description'))
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->columnSpan(4),

            Stat::make(__('filament.widgets.total_books'), Book::count())
                ->description(__('filament.widgets.total_books_description'))
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info')
                ->columnSpan(4),

            Stat::make(__('filament.widgets.total_ratings'), Rating::count())
                ->description(__('filament.widgets.total_ratings_description'))
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->columnSpan(4),

            Stat::make(__('filament.widgets.total_plans'), Plan::count())
                ->description(__('filament.widgets.total_plans_description'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->columnSpan(4),

            Stat::make(__('filament.widgets.total_admins'), Admin::count())
                ->description(__('filament.widgets.total_admins_description'))
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('danger')
                ->columnSpan(4),

            Stat::make('اكتر كاتب كتب مقالات', $topAuthorName)
                ->description("عدد مقالاته: {$topAuthorBooks}")
                ->color('primary')
                ->columnSpan(4),

            Stat::make('اكتر من ربح نقاط', $topPointsName)
                ->description("النقاط: {$topPointsValue}")
                ->color('success')
                ->columnSpan(4),

            Stat::make('اكتر من ربح أموال', $topMoneyName)
                ->description("الأرباح: {$topMoneyValue}")
                ->color('warning')
                ->columnSpan(4),

            Stat::make('اكتر كتاب مشاهده', $topBookTitle)
                ->description("عدد المشاهدات: {$topBookViews}")
                ->color('info')
                ->columnSpan(4),

            Stat::make('اكتر ادمن راجع مقالات', $topAdminName)
                ->description("عدد المراجعات: {$topAdminReviews}")
                ->color('danger')
                ->columnSpan(4),
        ];
    }
}
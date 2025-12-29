<?php

namespace App\Filament\Resources\Books\Pages;

use App\Models\Book;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Services\FirebaseNotificationService;
use App\Filament\Resources\Books\BookResource;


class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('changePendingBooksStatus')
                ->label('تغيير حالة الكتب المعلقة')
                ->icon('heroicon-o-adjustments-horizontal')
                ->color('primary')
                ->form([
                    Select::make('status')
                        ->label('الحالة الجديدة')
                        ->options([
                            'accepted' => 'مقبول',
                            'refused' => 'مرفوض',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $status = $data['status'];

                    $books = Book::where('status', 'pending')->get();

                    foreach ($books as $book) {
                        $book->status = $status;
                        $book->save();

                        if ($status === 'accepted' && $book->user_id) {
                            app(FirebaseNotificationService::class)
                                ->sendCustomNotification(
                                    'تم قبول كتابك',
                                    'تمت الموافقة على كتابك ويمكنك الآن الاطلاع عليه.',
                                    [$book->user_id],
                                    false,
                                    [
                                        'type' => 'book_accepted',
                                        'book_id' => (string) $book->id,
                                    ]
                                );
                        }

                        $followers = $book->user->followedBy()->pluck('users.id')->toArray();

                        if (!empty($followers)) {
                            app(FirebaseNotificationService::class)
                                ->sendCustomNotification(
                                    'كتاب جديد من المؤلف الذي تتابعه',
                                    "{$book->user->getNameAttribute()} نشر كتاب جديد: {$book->title}",
                                    $followers,
                                    false,
                                    [
                                        'type' => 'new_book',
                                        'book_id' => (string) $book->id,
                                        'author_id' => (string) $book->user->id,
                                    ]
                                );
                        }

                    }

                    Notification::make()
                        ->success()
                        ->title('تم تحديث حالة الكتب المعلقة بنجاح')
                        ->send();
                }),
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(fn () => \App\Models\Book::count()),

            'pending' => Tab::make('قيد المراجعة')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'pending')
                )
                ->badge(fn () => \App\Models\Book::where('status', 'pending')->count()),

            'accepted' => Tab::make('مقبول')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'accepted')
                )
                ->badge(fn () => \App\Models\Book::where('status', 'accepted')->count()),

            'refused' => Tab::make('مرفوض')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'refused')
                )
                ->badge(fn () => \App\Models\Book::where('status', 'refused')->count()),

            Tab::make('منشور')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('publish_status', 'published'))
                ->badge(fn () => \App\Models\Book::where('publish_status', 'published')->count()),

            Tab::make('مسودة')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('publish_status', 'draft'))
                ->badge(fn () => \App\Models\Book::where('publish_status', 'draft')->count()),

            Tab::make('مجدول')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('publish_status', 'scheduled'))
                ->badge(fn () => \App\Models\Book::where('publish_status', 'scheduled')->count()),    
        ];
    }

}

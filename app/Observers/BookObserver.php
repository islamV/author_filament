<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Admin;
use Filament\Notifications\Notification;

class BookObserver
{
    public function created(Book $book): void
    {
        $admins = Admin::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(
                Notification::make()
                    ->title('تم إضافة كتاب جديد')
                    ->body('تم إضافة كتاب جديد بعنوان: ' . ($book->title ?? 'بدون عنوان'))
                    ->success()
                    ->toDatabase()
            );
        }
    }

    public function updated(Book $book): void
    {
        $admins = Admin::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();


        foreach ($admins as $admin) {
            $admin->notify(
                Notification::make()
                    ->title('تم تعديل كتاب')
                    ->body('تم تعديل بيانات الكتاب: ' . ($book->title ?? 'بدون عنوان'))
                    ->success()
                    ->toDatabase()
            );
        }
    }
}

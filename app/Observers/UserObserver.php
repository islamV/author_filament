<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Admin;
use Filament\Notifications\Notification;

class UserObserver
{
    public function created(User $user): void
    {
        $admins = Admin::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(
                Notification::make()
                    ->title('تم إضافة مستخدم جديد')
                    ->body('تم إضافة مستخدم جديد باسم: ' . ($user->name ?? 'بدون اسم'))
                    ->success()
                    ->toDatabase()
            );
        }
    }

    public function updated(User $user): void
    {
        $admins = Admin::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();


        foreach ($admins as $admin) {
            $admin->notify(
                Notification::make()
                    ->title('تم تعديل مستخدم')
                    ->body('تم تعديل بيانات المستخدم: ' . ($user->name ?? 'بدون اسم'))
                    ->success()
                    ->toDatabase()
            );
        }
    }
}

<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MultiSelect;
use App\Filament\Resources\Users\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('changeStatusAll')
                ->label('تغيير حالة جميع المستخدمين')
                ->icon('heroicon-o-adjustments-horizontal')
                ->color('primary')
                ->form([
                    Select::make('status')
                        ->label('الحالة الجديدة')
                        ->options([
                            'active' => 'نشط',
                            'pending' => 'قيد المراجعة',
                            'suspended' => 'موقوف',
                            'refused' => 'مرفوض',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $status = $data['status'];
                    $previouslyInactiveUsers = \App\Models\User::where('is_active', false)
                        ->where('role_id', 3)
                        ->get();
                    \App\Models\User::query()->update([
                        'status' => $status,
                        'is_active' => $status === 'active',
                    ]);
                    if ($status === 'active' && $previouslyInactiveUsers->isNotEmpty()) {
                        $newlyActiveUserIds = $previouslyInactiveUsers->pluck('id')->toArray();
                        app(\App\Services\FirebaseNotificationService::class)
                            ->sendCustomNotification(
                                'تم تفعيل حسابك بنجاح',
                                'تم قبول حسابك ككاتب على المنصة ويمكنك الآن البدء في استخدام جميع المميزات.',
                                $newlyActiveUserIds
                            );
                    }
                    Notification::make()
                        ->success()
                        ->title('تم تحديث حالة جميع المستخدمين بنجاح')
                        ->send();
                }),

              Action::make('activateNewWriters')
            ->label('تفعيل الكتاب الجدد مباشرة')
            ->color('success')
            ->visible(fn () => !\App\Models\Setting::first()->new_writers_auto_active)
            ->action(function () {
                $setting = \App\Models\Setting::first();
                $setting->new_writers_auto_active = true;
                $setting->save();

                Notification::make()
                    ->success()
                    ->title('تم تفعيل الكتاب الجدد مباشرة عند التسجيل')
                    ->send();
            }),

        Action::make('deactivateNewWriters')
            ->label('إلغاء تفعيل الكتاب الجدد مباشرة')
            ->color('danger')
            ->visible(fn () => \App\Models\Setting::first()->new_writers_auto_active)
            ->action(function () {
                $setting = \App\Models\Setting::first();
                $setting->new_writers_auto_active = false;
                $setting->save();

                Notification::make()
                    ->success()
                    ->title('تم إلغاء تفعيل الكتاب الجدد مباشرة عند التسجيل')
                    ->send();
            }),

        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(fn () => \App\Models\User::count()),

            'pending' => Tab::make('قيد المراجعة')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'pending')
                )
                ->badge(fn () => \App\Models\User::where('status', 'pending')->count()),

            'refused' => Tab::make('مرفوض')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'refused')
                )
                ->badge(fn () => \App\Models\User::where('status', 'refused')->count()),

            'غير مفعل' => Tab::make('غير مفعل')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('is_active', false)
                )
                ->badge(fn () => \App\Models\User::where('is_active', false)->count()),
        ];
    }

}

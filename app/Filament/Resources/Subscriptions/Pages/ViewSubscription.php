<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Subscriptions\SubscriptionResource;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // EditAction::make(),
           
        Action::make('download_payment')
            ->label('تحميل إثبات الدفع')
            ->action(function ($record) {
                if ($record->manualPayment && $record->manualPayment->payment_screen_shot) {
                    return Storage::disk('public')->download($record->manualPayment->payment_screen_shot);
                }

                Notification::make()
                    ->title('لا يوجد ملف للتحميل')
                    ->danger()
                    ->send();
            })
            ->disabled(fn ($record) => !$record->manualPayment || !$record->manualPayment->payment_screen_shot),

        Action::make('update_status')
            ->label('تحديث الحالة')
            ->form([
                \Filament\Forms\Components\Select::make('status')
                    ->label('الحالة الجديدة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'active' => 'نشط',
                        'cancelled' => 'ملغى',
                        'expired' => 'منتهي',
                    ])
                    ->required(),
            ])
            ->action(function ($record, array $data): void {
                $oldStatus = $record->status;
                $newStatus = $data['status'];

                $record->update([
                    'status' => $newStatus,
                ]);

                if ($oldStatus !== 'active' && $newStatus === 'active') {
                    $user = $record->user;

                    if (in_array($user->role_id, [2, 3])) {
                        $pointsToAdd = $record->plan->reward_points ?? 0;
                        $user->increment('package_points', $pointsToAdd);
                    }
                }

                Notification::make()
                    ->title('تم تحديث حالة الاشتراك بنجاح')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->color('primary'),

        ];
    }
}

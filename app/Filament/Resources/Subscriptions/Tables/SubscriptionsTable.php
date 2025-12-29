<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('المعرف')->sortable(),
                TextColumn::make('user.name')->label('المستخدم')->sortable()->searchable(),
                TextColumn::make('plan.name')->label('الخطة')->sortable()->searchable(),
                TextColumn::make('paymentGateway.payment_gateway')->label('بوابة الدفع')->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->sortable()
                    ->badge()
                    ->getStateUsing(fn ($record) => match($record->status) {
                        'pending' => 'قيد الانتظار',
                        'active' => 'نشط',
                        'cancelled' => 'ملغى',
                        'expired' => 'منتهي',
                        default => $record->status,
                    }),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime()->sortable(),
                TextColumn::make('updated_at')->label('آخر تعديل')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                ->label('فلتر حسب الحالة')
                ->options([
                    'pending' => 'قيد الانتظار',
                    'active' => 'نشط',
                    'cancelled' => 'ملغى',
                    'expired' => 'منتهي',
                ]),
                SelectFilter::make('plan_id')
                    ->label('فلتر حسب الخطة')
                    ->relationship('plan', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
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
              //  EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

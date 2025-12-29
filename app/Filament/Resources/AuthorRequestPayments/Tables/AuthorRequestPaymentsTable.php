<?php

namespace App\Filament\Resources\AuthorRequestPayments\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use App\Services\AuthorRevenueService;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class AuthorRequestPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requested_amount')
                    ->label('المبلغ المطلوب')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('user_id')
                    ->label('المستخدم')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->getStateUsing(fn ($record) => match($record->status) {
                        'pending'  => 'قيد الانتظار',
                        'approved' => 'تمت الموافقة',
                        'rejected' => 'مرفوض',
                        'paid'     => 'تم الدفع',
                        default    => $record->status,
                    }),

                TextColumn::make('payment_method')
                    ->label('طريقة الدفع')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('beneficiary_name')
                    ->label('اسم المستفيد')
                    ->searchable(),

                TextColumn::make('bank_name')
                    ->label('اسم البنك')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('iban')
                    ->label('رقم الآيبان')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('wallet_id')
                    ->label('معرف المحفظة')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('wallet_name')
                    ->label('اسم المحفظة')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email_binance')
                    ->label('البريد الإلكتروني (Binance)')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('beneficiary_address')
                    ->label('عنوان المستفيد')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('swift_bio_code')
                    ->label('رمز SWIFT/BIC')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('update_status')
                ->label('تحديث الحالة')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->label('الحالة الجديدة')
                        ->options([
                            'pending'  => 'قيد الانتظار',
                            'approved' => 'تمت الموافقة',
                            'paid'     => 'تم الدفع',
                            'rejected' => 'مرفوض',
                        ])
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    if ($data['status'] === 'paid' && $record->user) {
                        $author = $record->user;
                        $wallet = \App\Models\Wallet::getOrCreateByUser($author);

                        $service = app(\App\Services\AuthorRevenueService::class);
                        $revenueData = $service->calculate($author);

                        if ($revenueData['availableBalance'] >= $record->requested_amount) {
                            $wallet->deduct($record->requested_amount);
                            $wallet->total_earned = $revenueData['totalPaid'] + $record->requested_amount;
                            $wallet->save();
                            $record->update(['status' => 'paid']);


                            app(\App\Services\FirebaseNotificationService::class)->sendCustomNotification(
                                'تم تحويل المبلغ',
                                'تم تحويل المبلغ بنجاح.',
                                [$record->user_id],
                                false,
                                [
                                    'type' => 'payment_approved',
                                    'payment_id' => (string) $record->id,
                                ]
                            );

                            \Filament\Notifications\Notification::make()
                                ->title('تم الدفع بنجاح')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('الرصيد غير كافي')
                                ->danger()
                                ->send();
                        }
                    } else {
                        $record->update(['status' => $data['status']]);

                        \Filament\Notifications\Notification::make()
                            ->title('تم تحديث حالة طلب الدفع بنجاح')
                            ->success()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->color('primary'),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

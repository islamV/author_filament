<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FirebaseNotificationService;
use Filament\Forms\Components\DateTimePicker;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->recordClasses(fn (?Model $record): string => 
                $record && $record->status === 'refused'
                    ? 'bg-red-50 dark:bg-red-900/20'
                    : ''
            )
            ->columns([

                ImageColumn::make('image')
                    ->label(__('filament.users.image')),

                TextColumn::make('first_name')
                    ->label(__('filament.users.first_name'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('last_name')
                    ->label(__('filament.users.last_name'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('email')
                    ->label(__('filament.users.email'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),

               // ToggleColumn::make('is_trial_user')
                //    ->label('تجربة مجانية'),    
                TextColumn::make('email_verified_at')
                    ->label(__('filament.users.email_verified_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('status')
                    ->label(__('filament.users.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("filament.status.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        'refused' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('refuse_reason')
                    ->label(__('filament.users.refuse_reason'))
                    ->limit(50)
                    ->tooltip(fn (?Model $record): ?string => $record?->refuse_reason)
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (?Model $record): bool => $record && $record->status === 'refused' && !empty($record->refuse_reason)),
                TextColumn::make('downloads')
                    ->label(__('filament.users.downloads'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('phone')
                    ->label(__('filament.users.phone'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('address')
                    ->label(__('filament.users.address'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('otp')
                    ->label(__('filament.users.otp'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('otp_valid_until')
                    ->label(__('filament.users.otp_valid_until'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('work_link')
                    ->label(__('filament.users.work_link'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('role.name')
                    ->label(__('filament.users.role_id'))
                    ->numeric()
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('created_at')
                    ->label(__('filament.users.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('updated_at')
                    ->label(__('filament.users.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('stripe_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.users.stripe_id'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('pm_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.users.pm_type'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('pm_last_four')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.users.pm_last_four'))
                    ->searchable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),
                TextColumn::make('trial_ends_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.users.trial_ends_at'))
                    ->dateTime()
                    ->sortable()
                    ->extraAttributes(fn (?Model $record): array => [
                        'style' => $record && $record->status === 'refused' ? 'color: red;' : '',
                    ]),

                TextColumn::make('registrationCountry.name')
                        ->label('الدولة')
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')

                    ->label(__('filament.users.status'))
                    ->options([
                        'active' => __('filament.status.active'),
                        'pending' => __('filament.status.pending'),
                        'suspended' => __('filament.status.suspended'),
                        'refused' => __('filament.status.refused'),
                    ]),
                SelectFilter::make('role_id')
                    ->label(__('filament.users.role_id'))
                    ->relationship('role', 'name', fn($query) => $query->where('guard_name', 'web')),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make()->button(),
                EditAction::make()->button(),
                Action::make('add_free_trial')
                    ->label('إضافة تجربة مجانية')
                    ->form([
                        DateTimePicker::make('trial_expires_at')
                            ->label('تاريخ انتهاء التجربة')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'is_trial_user' => true,
                            'trial_expires_at' => $data['trial_expires_at'],
                        ]);

                        Notification::make()
                            ->title('تمت إضافة التجربة المجانية بنجاح')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('success'),
                Action::make('activate')
                    ->label(__('filament.actions.activate'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Model $record) {
                        $record->update([
                            'status' => 'active',
                            'is_active' => true,
                            'refuse_reason' => null,
                        ]);

                        if ($record->role_id === 3 && $record->device_token) {
                            app(FirebaseNotificationService::class)->sendCustomNotification(
                                'تم تفعيل حسابك بنجاح',
                                'تم قبول حسابك ككاتب على المنصة، ويمكنك الآن البدء في استخدام جميع المميزات.',
                                [$record->id],
                                false,
                                [
                                    'type' => 'author_approved',
                                    'user_id' => (string) $record->id,
                                ]
                            );
                        }



                        Notification::make()
                            ->success()
                            ->title(__('filament.users.activated_successfully'))
                            ->send();
                    })
                    ->visible(fn (?Model $record): bool => 
                        $record && 
                        $record->role_id != 2 && 
                        $record->status !== 'active'
                    )
                    ->button(),
                Action::make('refuse')
                    ->label(__('filament.actions.refuse'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('refuse_reason')
                            ->label(__('filament.users.refuse_reason'))
                            ->rows(3)
                            ->maxLength(500)
                            ->required()
                            ->reactive(),
                    ])
                    ->action(function (Model $record, array $data) {
                        if (empty($data['refuse_reason'])) {
                            Notification::make()
                                ->danger()
                                ->title(__('filament.users.refuse_reason_required'))
                                ->send();
                            return;
                        }

                        $record->update([
                            'status' => 'refused',
                            'is_active' => false,
                            'refuse_reason' => $data['refuse_reason'],
                        ]);

                        Notification::make()
                            ->danger()
                            ->title(__('filament.users.refused_successfully'))
                            ->send();
                    })
                    ->visible(fn (?Model $record): bool => 
                        $record && 
                        $record->role_id != 2 && 
                        $record->status !== 'refused'
                    )
                    ->button(),

                    Action::make('toggleRole')
                        ->label('تغيير الدور')
                        ->icon('heroicon-o-x-circle')
                        ->color('primary')
                        ->action(function (Model $record) {
                            if ($record->role_id === 3) {
                                $record->role_id = 4; // Author → Reader
                                $message = 'تم تغيير الدور إلى قارئ.';
                            } elseif ($record->role_id === 4) {
                                $record->role_id = 3; // Reader → Author
                                $message = 'تم تغيير الدور إلى مؤلف.';
                            } else {
                                Notification::make()
                                    ->danger()
                                    ->title('يمكن فقط تحويل المؤلفين أو القراء.')
                                    ->send();
                                return;
                            }

                            $record->save();

                            Notification::make()
                                ->success()
                                ->title($message)
                                ->send();
                        })
                        ->visible(fn (?Model $record): bool => $record && in_array($record->role_id, [3, 4]))
                        ->button(),

            ])
                   

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('changeStatus')
                    ->label('تغيير حالة المستخدمين')
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
                    ->action(function (Collection $records, array $data) {
                        foreach ($records as $record) {
                            $record->update([
                                'status' => $data['status'],
                                'is_active' => $data['status'] === 'active',
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('تم تحديث حالة المستخدمين بنجاح')
                            ->send();
                    }),

                ]),
            ]);
    }
}

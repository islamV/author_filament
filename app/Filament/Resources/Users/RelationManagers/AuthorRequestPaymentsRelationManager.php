<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Users\UserResource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\RelationManagers\RelationManager;

class AuthorRequestPaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'authorRequestPayments';

    protected static ?string $relatedResource = UserResource::class;

    public static function getModelLabel(): string
    {
        return 'طلب سحب الأموال';
    }

    public static function getPluralModelLabel(): string
    {
        return 'طلبات سحب الأموال';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'طلب سحب الأموال';
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('requested_amount')
                ->label('المبلغ المطلوب')
                ->numeric()
                ->required()
                ->minValue(1),

            Select::make('status')
                ->label('الحالة')
                ->options([
                    'pending' => 'قيد الانتظار',
                    'approved' => 'تمت الموافقة',
                    'rejected' => 'مرفوض',
                    'paid' => 'تم الدفع',
                ])
                ->required()
                ->default('pending'),

            Select::make('payment_method')
                ->label('طريقة الدفع')
                ->options([
                    'bank_transfer' => 'تحويل بنكي',
                    'wallet' => 'محفظة إلكترونية',
                    'binance' => 'باينانس',
                ])
                ->required()
                ->reactive(),

            TextInput::make('phone')
                ->label('رقم الهاتف')
                ->tel()
                ->maxLength(20),

            
            TextInput::make('beneficiary_name')
                ->label('اسم المستفيد')
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'bank_transfer'),

            TextInput::make('bank_name')
                ->label('اسم البنك')
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'bank_transfer'),

            TextInput::make('iban')
                ->label('رقم الآيبان')
                ->maxLength(34)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'bank_transfer'),

            TextInput::make('beneficiary_address')
                ->label('عنوان المستفيد')
                ->maxLength(500)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'bank_transfer'),

            TextInput::make('swift_bio_code')
                ->label('رمز SWIFT/BIC')
                ->maxLength(11)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'bank_transfer'),

            // Wallet fields
            TextInput::make('wallet_name')
                ->label('اسم المحفظة')
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'wallet'),

            TextInput::make('wallet_id')
                ->label('رقم/معرّف المحفظة')
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'wallet'),

            // Binance fields
            TextInput::make('email_binance')
                ->label('البريد الإلكتروني لباينانس')
                ->email()
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('payment_method') === 'binance'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invitation_code')
            ->columns([
                Tables\Columns\TextColumn::make('requested_amount')
                    ->label('المبلغ المطلوب')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'paid' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'approved' => 'تمت الموافقة',
                        'rejected' => 'مرفوض',
                        'paid' => 'تم الدفع',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('طريقة الدفع')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'تحويل بنكي',
                        'wallet' => 'محفظة إلكترونية',
                        'binance' => 'باينانس',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('اسم البنك')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('iban')
                    ->label('رقم الآيبان')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
               // ViewAction::make(),
            ]);
    }
}

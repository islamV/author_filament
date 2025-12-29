<?php

namespace App\Filament\Resources\AuthorRequestPayments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class AuthorRequestPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الدفع')
                    ->schema([
                        TextInput::make('requested_amount')
                            ->label('المبلغ المطلوب')
                            ->required()
                            ->numeric(),

                        TextInput::make('user_id')
                            ->label('المستخدم')
                            ->required()
                            ->numeric(),

                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'pending' => 'معلق',
                                'approved' => 'تمت الموافقة',
                                'rejected' => 'مرفوض',
                                'paid'     => 'تم الدفع',
                            ])
                            ->default('pending')
                            ->required(),

                        TextInput::make('payment_method')
                            ->label('طريقة الدفع')
                            ->required(),
                    ])->columnSpanFull()
                    ->columns(2),

                Section::make('تفاصيل المستفيد')
                    ->schema([
                        TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->default(null),

                        TextInput::make('beneficiary_name')
                            ->label('اسم المستفيد')
                            ->default(null),

                        TextInput::make('bank_name')
                            ->label('اسم البنك')
                            ->default(null),

                        TextInput::make('iban')
                            ->label('رقم الآيبان')
                            ->default(null),

                        TextInput::make('wallet_id')
                            ->label('معرف المحفظة')
                            ->default(null),

                        TextInput::make('wallet_name')
                            ->label('اسم المحفظة')
                            ->default(null),

                        TextInput::make('email_binance')
                            ->label('البريد الإلكتروني (Binance)')
                            ->email()
                            ->default(null),

                        TextInput::make('beneficiary_address')
                            ->label('عنوان المستفيد')
                            ->default(null),

                        TextInput::make('swift_bio_code')
                            ->label('رمز SWIFT/BIC')
                            ->default(null),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}

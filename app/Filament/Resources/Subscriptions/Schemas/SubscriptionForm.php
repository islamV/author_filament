<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الاشتراك')
                    ->schema([
                        Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'first_name')
                            ->required(),

                        Select::make('plan_id')
                            ->label('الخطة')
                            ->relationship('plan', 'name')
                            ->required(),

                        Select::make('payment_gateway_id')
                            ->label('بوابة الدفع')
                            ->relationship('paymentGateway', 'payment_gateway')
                            ->required(),
    

                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'active' => 'نشط',
                                'cancelled' => 'ملغى',
                                'expired' => 'منتهي',
                            ])
                            ->required(),

                        DatePicker::make('end_date')
                            ->label('تاريخ انتهاء الاشتراك')
                            ->format('Y-m-d')
                            ->placeholder('YYYY-MM-DD')
                            ->nullable(),    
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                    Section::make('بيانات الدفع')
                        ->relationship('manualPayment')
                        ->schema([
                            TextInput::make('manualPayment.bank_name')
                                ->label('اسم البنك'),

                            TextInput::make('manualPayment.account_number')
                                ->label('رقم الحساب'),

                            TextInput::make('manualPayment.beneficiary_name')
                                ->label('اسم المستفيد'),

                            TextInput::make('manualPayment.wallet_id')
                                ->label('رقم المحفظة'),

                            TextInput::make('manualPayment.wallet_name')
                                ->label('اسم صاحب المحفظة'),

                            TextInput::make('manualPayment.amount')
                                ->label('المبلغ المدفوع'),

                            TextInput::make('manualPayment.sender_number')
                                ->label('الرقم المرسل منه المبلغ'),

                            DatePicker::make('manualPayment.payment_date')
                                ->label('تاريخ الدفع'),

                            FileUpload::make('manualPayment.payment_screen_shot')
                                ->label('إثبات الدفع'),

                            TextInput::make('manualPayment.payment_address')
                                ->label('عنوان الدفع'),
                        ])
                        ->columns(2)
                        ->columnSpanFull()

            ]);
    }
}

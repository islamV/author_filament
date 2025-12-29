<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class SubscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الاشتراك')
                    ->schema([
                        TextEntry::make('user.first_name')
                            ->label('المستخدم'),


                        TextEntry::make('user.email')
                            ->label(__('filament.users.email')),

                        TextEntry::make('plan.name')
                            ->label('الخطة'),
                        TextEntry::make('status')
                            ->label('الحالة')
                            ->getStateUsing(fn ($record) => match($record->status) {
                                'pending' => 'قيد الانتظار',
                                'active' => 'نشط',
                                'cancelled' => 'ملغى',
                                'expired' => 'منتهي',
                                default => $record->status,
                            })
                            ->badge(),

                        TextEntry::make('paymentGateway.payment_gateway')
                            ->label('بوابة الدفع'),
                            
                        TextEntry::make('created_at')
                            ->label('تاريخ الإنشاء')
                            ->formatStateUsing(fn ($state) => $state->format('Y-m-d H:i')),

                        TextEntry::make('end_date')
                            ->label('تاريخ انتهاء الاشتراك')
                            ->formatStateUsing(fn ($state) => $state?->format('Y-m-d')),

                    ])
                    ->columnSpanFull()
                    ->columns(2),

                    
            Section::make('بيانات الدفع')
                ->schema([
                    TextEntry::make('manualPayment.bank_name')
                        ->label('اسم البنك'),

                    TextEntry::make('manualPayment.account_number')
                        ->label('رقم الحساب'),

                    TextEntry::make('manualPayment.beneficiary_name')
                        ->label('اسم المستفيد'),

                    TextEntry::make('manualPayment.wallet_id')
                        ->label('رقم المحفظة'),

                    TextEntry::make('manualPayment.wallet_name')
                        ->label('اسم صاحب المحفظة'),

                    TextEntry::make('manualPayment.amount')
                        ->label('المبلغ المدفوع'),

                    TextEntry::make('manualPayment.sender_number')
                        ->label('الرقم المرسل منه المبلغ'),

                    TextEntry::make('manualPayment.payment_date')
                        ->label('تاريخ الدفع'),

                    TextEntry::make('manualPayment.payment_address')
                        ->label('عنوان الدفع'),

                    ImageEntry::make('manualPayment.payment_screen_shot')
                        ->label('إثبات الدفع')
                        ->size(150)
                        ->disk('public'),
                ])
                ->columns(2)
                ->columnSpanFull(),
            ]);
    }
}

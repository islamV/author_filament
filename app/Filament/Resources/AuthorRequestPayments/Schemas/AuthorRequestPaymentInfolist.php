<?php

namespace App\Filament\Resources\AuthorRequestPayments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class AuthorRequestPaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الدفع')
                    ->schema([
                        TextEntry::make('requested_amount')
                            ->label('المبلغ المطلوب')
                            ->numeric(),

                        TextEntry::make('payment_method')
                            ->label('طريقة الدفع'),

                        TextEntry::make('status')
                            ->label('الحالة')
                            ->getStateUsing(fn ($record) => match($record->status) {
                                'pending' => 'معلق',
                                'approved' => 'تمت الموافقة',
                                'rejected' => 'مرفوض',
                                'paid'     => 'تم الدفع',
                                default => $record->status,
                            })
                            ->badge(),

                        TextEntry::make('phone')
                            ->label('رقم الهاتف')
                            ->placeholder('-'),
                    ])->columns(2)
                    ->columnSpanFull(),

                Section::make('بيانات المستفيد')
                    ->schema([
                        TextEntry::make('beneficiary_name')
                            ->label('اسم المستفيد')
                            ->placeholder('-'),

                        TextEntry::make('bank_name')
                            ->label('اسم البنك')
                            ->placeholder('-'),

                        TextEntry::make('iban')
                            ->label('رقم الآيبان')
                            ->placeholder('-'),

                        TextEntry::make('wallet_id')
                            ->label('معرف المحفظة')
                            ->placeholder('-'),

                        TextEntry::make('wallet_name')
                            ->label('اسم المحفظة')
                            ->placeholder('-'),

                        TextEntry::make('email_binance')
                            ->label('البريد الإلكتروني (Binance)')
                            ->placeholder('-'),

                        TextEntry::make('beneficiary_address')
                            ->label('عنوان المستفيد')
                            ->placeholder('-'),

                        TextEntry::make('swift_bio_code')
                            ->label('رمز SWIFT/BIC')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('معلومات عامة')
                    ->schema([
                        TextEntry::make('user.first_name')
                            ->label('المستخدم')
                            ->numeric(),

                        TextEntry::make('created_at')
                            ->label('تاريخ الإنشاء')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('تاريخ التحديث')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}

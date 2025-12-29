<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->extraAttributes(function ($record) {
                return [
                    'class' => $record && $record->status === 'refused'
                        ? 'bg-red-50 dark:bg-red-900/20 p-4 rounded-lg'
                        : '',
                ];
            })
            ->components([
                Section::make(__('filament.users.basic_information'))
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('filament.users.image'))
                            ->placeholder('-')
                            ->height(80),
                        TextEntry::make('first_name')
                            ->label(__('filament.users.first_name'))
                            ->placeholder('-'),
                        TextEntry::make('last_name')
                            ->label(__('filament.users.last_name'))
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label(__('filament.users.email')),
                        TextEntry::make('status')
                            ->label(__('filament.users.status'))
                            ->badge()
                            ->formatStateUsing(fn ($record): string => __("filament.status.{$record->status}"))
                            ->color(fn ($record): string => match ($record->status) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'suspended' => 'danger',
                                'refused' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.contact_information'))
                    ->schema([
                        TextEntry::make('phone')
                            ->label(__('filament.users.phone'))
                            ->placeholder('-'),
                        TextEntry::make('address')
                            ->label(__('filament.users.address'))
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('registrationCountry.name')
                            ->label('الدولة')
                            ->placeholder('-')
                            ->columnSpanFull(),
  
                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.account_settings'))
                    ->schema([
                        TextEntry::make('role.name')
                            ->label(__('filament.users.role_id'))
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('downloads')
                            ->label(__('filament.users.downloads'))
                            ->numeric(),
                        TextEntry::make('refuse_reason')
                            ->label(__('filament.users.refuse_reason'))
                            ->placeholder('-')
                            ->color('danger')
                            ->icon('heroicon-o-x-circle')
                            ->visible(fn ($record): bool => $record->status === 'refused' && !empty($record->refuse_reason))
                            ->columnSpanFull(),
                        TextEntry::make('email_verified_at')
                            ->label(__('filament.users.email_verified_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('trial_ends_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.additional_information'))
                    ->schema([
                        TextEntry::make('description')
                            ->label(__('filament.users.description'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('work_link')
                            ->label(__('filament.users.work_link'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('otp')
                            ->placeholder('-'),
                        TextEntry::make('otp_valid_until')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('stripe_id')
                            ->placeholder('-'),
                        TextEntry::make('pm_type')
                            ->placeholder('-'),
                        TextEntry::make('pm_last_four')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Section::make(__('filament.users.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.users.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.users.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('الدعوات')
                    ->schema([
                        TextEntry::make('invitation_code')
                            ->label('كود الدعوة')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('invitation_points')
                            ->label('نقاط الدعوات')
                            ->numeric()
                            ->placeholder('0'),

                        TextEntry::make('invitation_codes_count')
                            ->label('عدد المستخدمين الذين استخدموا الكود')
                            ->numeric()
                            ->getStateUsing(fn ($record) => $record->ownedInvitationCodes()->count()),

                        TextEntry::make('package_points')
                            ->label('نقاط الاشتراكات')
                            ->numeric()
                            ->placeholder('0'), 

    
                    ])
                    ->columns(2)
                    ->collapsible(), 


                    Section::make('المحفظة')
                        ->schema([
                            TextEntry::make('wallet.balance')
                                ->label('الرصيد المتاح')
                                ->numeric()
                                ->formatStateUsing(fn ($state) => number_format($state, 2))
                                ->placeholder('0.00'),

                            TextEntry::make('wallet.total_earned')
                                ->label('إجمالي المدفوعات')
                                ->numeric()
                                ->formatStateUsing(fn ($state) => number_format($state, 2))
                                ->placeholder('0.00'),
                        ])
                        ->columns(2)
                        ->collapsible(),
                    
                Section::make('التجربة المجانية')
                    ->schema([
                        TextEntry::make('is_trial_user')
                            ->label('هل المستخدم في فترة تجريبية؟')
                            ->formatStateUsing(fn($state) => $state ? 'نعم' : 'لا'),

                        TextEntry::make('trial_expires_at')
                            ->label('تاريخ انتهاء التجربة')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),

    
            ]);
    }
}

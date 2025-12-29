<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Country;
use Filament\Schemas\Schema;
use App\Models\RegistrationCountry;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.users.basic_information'))
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('filament.users.first_name'))
                            ->default(null),
                        TextInput::make('last_name')
                            ->label(__('filament.users.last_name'))
                            ->default(null),
                    
                        TextInput::make('password')
                            ->label(__('filament.users.password'))
                            ->password()
                            ->required()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state)),
                       
                        FileUpload::make('image')
                            ->label(__('filament.users.image'))
                            ->disk('public')
                            ->directory('users')
                            ->image(),

                        Textarea::make('description')
                            ->label(__('filament.users.description'))
                            ->default(null)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.contact_information'))
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('filament.users.phone'))
                            ->tel()
                            ->default(null)
                            ->unique(),

                        TextInput::make('email')
                            ->label(__('filament.users.email'))
                            ->email()
                            ->required()
                            ->unique(),

                        TextInput::make('address')
                            ->label(__('filament.users.address'))
                            ->default(null)
                            ->columnSpanFull(),

                        Select::make('country_id')
                            ->label('الدولة')
                            ->options(RegistrationCountry::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->placeholder('اختر الدولة')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.account_settings'))
                    ->schema([
                        Select::make('role_id')
                            ->label(__('filament.users.role_id'))
                            ->relationship('role','name')
                            ->default(null)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, $record) {
                                // If role_id is 2 and current status is active or refused, reset to pending
                                if ($state == 2) {
                                    $currentStatus = $record?->status ?? 'pending';
                                    if (in_array($currentStatus, ['active', 'refused'])) {
                                        $set('status', 'pending');
                                    }
                                }
                            }),
                     
                        DateTimePicker::make('email_verified_at')
                            ->label(__('filament.users.email_verified_at')),
                        DateTimePicker::make('trial_ends_at'),

                         Select::make('status')
                            ->label(__('filament.users.status'))
                            ->options(function ($record, callable $get) {
                                $options = [
                                    'pending' => __('filament.status.pending'),
                                    'suspended' => __('filament.status.suspended'),
                                ];
                                
                                // Get role_id from form or record
                                $roleId = $get('role_id') ?? $record?->role_id;
                                
                                // If role_id is not 2, add active and refused options
                                if ($roleId != 2) {
                                    $options['active'] = __('filament.status.active');
                                    $options['refused'] = __('filament.status.refused');
                                }
                                
                                return $options;
                            })
                            ->default('pending')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, $record, callable $get) {
                                // If role_id is 2 and status is active or refused, reset to pending
                                $roleId = $get('role_id') ?? $record?->role_id;
                                if ($roleId == 2 && in_array($state, ['active', 'refused'])) {
                                    $set('status', 'pending');
                                }
                            }),

                    ])
                    ->columns(2),
                
                Section::make(__('filament.users.additional_information'))
                    ->schema([


                           TextInput::make('downloads')
                            ->label(__('filament.users.downloads'))
                            ->required()
                            ->numeric()
                            ->default(0),


                     
                        TextInput::make('work_link')
                            ->label(__('filament.users.work_link'))
                            ->default(null)
                            ->columnSpanFull(),
                        TextInput::make('otp')
                            ->default(null),
                        DateTimePicker::make('otp_valid_until'),
                        TextInput::make('stripe_id')
                            ->default(null),
                        TextInput::make('pm_type')
                            ->default(null),
                        TextInput::make('pm_last_four')
                            ->default(null),
                    ])
                    ->columns(2)
                    ->collapsible(),


                Section::make('التجربة المجانية')
                    ->schema([
                        Toggle::make('is_trial_user')
                            ->label('هل المستخدم في فترة تجريبية؟'), 
                        DateTimePicker::make('trial_expires_at')
                            ->label('تاريخ انتهاء التجربة')
                            ->placeholder('-')
                            ->required(fn ($get) => $get('is_trial_user')),
                    ])
                    ->columns(2)
                    ->collapsible(),
    
            ]);
    }
}

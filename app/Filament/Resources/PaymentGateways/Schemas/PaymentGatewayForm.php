<?php

namespace App\Filament\Resources\PaymentGateways\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentGatewayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.payment_gateways.basic_information'))
                    ->schema([
                        TextInput::make('payment_gateway')
                            ->label(__('filament.payment_gateways.payment_gateway'))
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label(__('filament.payment_gateways.is_active'))
                            ->default(true),
                        TextInput::make('phone_number')
                            ->label(__('filament.payment_gateways.phone_number'))
                            ->tel(),
                        TextInput::make('account_number')
                            ->label(__('filament.payment_gateways.account_number')),
                        TextInput::make('bank_name')
                            ->label(__('filament.payment_gateways.bank_name')),
                        TextInput::make('receiver_name')
                            ->label(__('filament.payment_gateways.receiver_name')),
                    ])
                    ->columns(2),
            ]);
    }
}


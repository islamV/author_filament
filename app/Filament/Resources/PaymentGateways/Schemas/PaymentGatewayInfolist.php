<?php

namespace App\Filament\Resources\PaymentGateways\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentGatewayInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.payment_gateways.basic_information'))
                    ->schema([
                        TextEntry::make('payment_gateway')
                            ->label(__('filament.payment_gateways.payment_gateway')),
                        IconEntry::make('is_active')
                            ->label(__('filament.payment_gateways.is_active'))
                            ->boolean(),
                        TextEntry::make('phone_number')
                            ->label(__('filament.payment_gateways.phone_number'))
                            ->placeholder('-'),
                        TextEntry::make('account_number')
                            ->label(__('filament.payment_gateways.account_number'))
                            ->placeholder('-'),
                        TextEntry::make('bank_name')
                            ->label(__('filament.payment_gateways.bank_name'))
                            ->placeholder('-'),
                        TextEntry::make('receiver_name')
                            ->label(__('filament.payment_gateways.receiver_name'))
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.payment_gateways.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.payment_gateways.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.payment_gateways.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}


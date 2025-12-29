<?php

namespace App\Filament\Resources\PaymentGateways\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentGatewaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                TextColumn::make('payment_gateway')
                    ->label(__('filament.payment_gateways.payment_gateway'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label(__('filament.payment_gateways.phone_number'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('account_number')
                    ->label(__('filament.payment_gateways.account_number'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('bank_name')
                    ->label(__('filament.payment_gateways.bank_name'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('receiver_name')
                    ->label(__('filament.payment_gateways.receiver_name'))
                    ->searchable()
                    ->toggleable(),
                ToggleColumn::make('is_active')
                    ->label(__('filament.payment_gateways.is_active'))
                  ,
                TextColumn::make('created_at')
                    ->label(__('filament.payment_gateways.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.payment_gateways.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label(__('filament.payment_gateways.is_active'))
                    ->options([
                        1 => __('filament.actions.yes'),
                        0 => __('filament.actions.no'),
                    ]),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}


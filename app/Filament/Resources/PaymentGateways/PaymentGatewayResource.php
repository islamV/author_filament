<?php

namespace App\Filament\Resources\PaymentGateways;

use App\Filament\Resources\PaymentGateways\Pages\CreatePaymentGateway;
use App\Filament\Resources\PaymentGateways\Pages\EditPaymentGateway;
use App\Filament\Resources\PaymentGateways\Pages\ListPaymentGateways;
use App\Filament\Resources\PaymentGateways\Pages\ViewPaymentGateway;
use App\Filament\Resources\PaymentGateways\Schemas\PaymentGatewayForm;
use App\Filament\Resources\PaymentGateways\Schemas\PaymentGatewayInfolist;
use App\Filament\Resources\PaymentGateways\Tables\PaymentGatewaysTable;
use App\Models\PaymentGateway;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'payment_gateway';

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.payment_gateways');
    }

    public static function getModelLabel(): string
    {
        return __('filament.payment_gateways.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.payment_gateways.plural_label');
    }


    public static function getNavigationGroup(): ?string
    {
        return 'الاشتراكات والخطط';
    }

    public static function form(Schema $schema): Schema
    {
        return PaymentGatewayForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentGatewayInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentGatewaysTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentGateways::route('/'),
            //'create' => CreatePaymentGateway::route('/create'),
            'view' => ViewPaymentGateway::route('/{record}'),
           // 'edit' => EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}


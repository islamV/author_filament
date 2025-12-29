<?php

namespace App\Filament\Resources\AuthorRequestPayments;

use App\Filament\Resources\AuthorRequestPayments\Pages\CreateAuthorRequestPayment;
use App\Filament\Resources\AuthorRequestPayments\Pages\EditAuthorRequestPayment;
use App\Filament\Resources\AuthorRequestPayments\Pages\ListAuthorRequestPayments;
use App\Filament\Resources\AuthorRequestPayments\Pages\ViewAuthorRequestPayment;
use App\Filament\Resources\AuthorRequestPayments\Schemas\AuthorRequestPaymentForm;
use App\Filament\Resources\AuthorRequestPayments\Schemas\AuthorRequestPaymentInfolist;
use App\Filament\Resources\AuthorRequestPayments\Tables\AuthorRequestPaymentsTable;
use App\Models\AuthorRequestPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuthorRequestPaymentResource extends Resource
{
    protected static ?string $model = AuthorRequestPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AuthorRequestPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuthorRequestPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorRequestPaymentsTable::configure($table);
    }


    public static function getNavigationLabel(): string
    {
        return 'طلبات السحب';
    }

    public static function getModelLabel(): string
    {
        return 'طلب سحب';
    }

    public static function getPluralModelLabel(): string
    {
        return 'طلبات السحب';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'الاشتراكات والخطط';
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
            'index' => ListAuthorRequestPayments::route('/'),
            'create' => CreateAuthorRequestPayment::route('/create'),
            'view' => ViewAuthorRequestPayment::route('/{record}'),
            'edit' => EditAuthorRequestPayment::route('/{record}/edit'),
        ];
    }
}

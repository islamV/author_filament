<?php

namespace App\Filament\Resources\AuthorPayments;

use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\AuthorPayment;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\AuthorPayments\Pages\EditAuthorPayment;
use App\Filament\Resources\AuthorPayments\Pages\ViewAuthorPayment;
use App\Filament\Resources\AuthorPayments\Pages\ListAuthorPayments;
use App\Filament\Resources\AuthorPayments\Pages\CreateAuthorPayment;
use App\Filament\Resources\AuthorPayments\Schemas\AuthorPaymentForm;
use App\Filament\Resources\AuthorPayments\Tables\AuthorPaymentsTable;
use App\Filament\Resources\AuthorPayments\Schemas\AuthorPaymentInfolist;

class AuthorPaymentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $shouldRegisterNavigation = false;


    public static function getNavigationLabel(): string
    {
        return 'أرباح المؤلفين';
    }

    public static function getModelLabel(): string
    {
        return 'أرباح المؤلف';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أرباح المؤلفين';
    }


    public static function getNavigationGroup(): ?string
    {
        return 'الاشتراكات والخطط';
    }


    public static function form(Schema $schema): Schema
    {
        return AuthorPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuthorPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorPaymentsTable::configure($table);
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
            'index' => ListAuthorPayments::route('/'),
            'create' => CreateAuthorPayment::route('/create'),
            'view' => ViewAuthorPayment::route('/{record}'),
            'edit' => EditAuthorPayment::route('/{record}/edit'),
        ];
    }
}

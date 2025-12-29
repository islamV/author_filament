<?php

namespace App\Filament\Resources\RegistrationCountries;

use App\Filament\Resources\RegistrationCountries\Pages\CreateRegistrationCountry;
use App\Filament\Resources\RegistrationCountries\Pages\EditRegistrationCountry;
use App\Filament\Resources\RegistrationCountries\Pages\ListRegistrationCountries;
use App\Filament\Resources\RegistrationCountries\Pages\ViewRegistrationCountry;
use App\Filament\Resources\RegistrationCountries\Schemas\RegistrationCountryForm;
use App\Filament\Resources\RegistrationCountries\Schemas\RegistrationCountryInfolist;
use App\Filament\Resources\RegistrationCountries\Tables\RegistrationCountriesTable;
use App\Models\RegistrationCountry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationCountryResource extends Resource
{
    protected static ?string $model = RegistrationCountry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RegistrationCountry';


    public static function getNavigationLabel(): string
    {
        return 'الدول';
    }

    public static function getModelLabel(): string
    {
        return 'دولة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الدول';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'الدول';
    }


    public static function form(Schema $schema): Schema
    {
        return RegistrationCountryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RegistrationCountryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationCountriesTable::configure($table);
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
            'index' => ListRegistrationCountries::route('/'),
            'create' => CreateRegistrationCountry::route('/create'),
            'view' => ViewRegistrationCountry::route('/{record}'),
            'edit' => EditRegistrationCountry::route('/{record}/edit'),
        ];
    }
}

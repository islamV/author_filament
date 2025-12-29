<?php

namespace App\Filament\Resources\Ads;

use App\Filament\Resources\Ads\Pages\CreateAd;
use App\Filament\Resources\Ads\Pages\EditAd;
use App\Filament\Resources\Ads\Pages\ListAds;
use App\Filament\Resources\Ads\Pages\ViewAd;
use App\Filament\Resources\Ads\Schemas\AdForm;
use App\Filament\Resources\Ads\Schemas\AdInfolist;
use App\Filament\Resources\Ads\Tables\AdsTable;
use App\Models\Ad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.ads');
    }

    public static function getModelLabel(): string
    {
        return __('filament.ads.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.ads.plural_label');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return Ad::count();
    }

    public static function form(Schema $schema): Schema
    {
        return AdForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AdInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdsTable::configure($table);
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
            'index' => ListAds::route('/'),
            'create' => CreateAd::route('/create'),
            'view' => ViewAd::route('/{record}'),
            'edit' => EditAd::route('/{record}/edit'),
        ];
    }
}

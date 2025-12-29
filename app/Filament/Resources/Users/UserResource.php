<?php

namespace App\Filament\Resources\Users;

use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\RelationManagers\FollowedByRelationManager;
use App\Filament\Resources\Users\RelationManagers\AuthorRequestPaymentsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $recordTitleAttribute = 'last_name';

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.users');
    }

    public static function getModelLabel(): string
    {
        return __('filament.users.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.users.plural_label');
    }

    public static function getNavigationBadge(): ?string
    {
        return User::count();
    }


    public static function getNavigationGroup(): ?string
    {
        return 'المستخدمين';
    }


    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Users\RelationManagers\InvitationCodesRelationManager::class,
            AuthorRequestPaymentsRelationManager::class,
            FollowedByRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }


    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->label(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index'))
                ->icon(static::$navigationIcon)
                ->badge(User::count()),

            NavigationItem::make()
                ->label('مفعل') 
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'active']))
                ->icon('heroicon-o-check-circle')
                ->badge(User::where('status', 'active')->count()),

            NavigationItem::make()
                ->label('معلق') 
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'pending']))
                ->icon('heroicon-o-clock')
                ->badge(User::where('status', 'pending')->count()),

            NavigationItem::make()
                ->label('موقف') 
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'suspended']))
                ->icon('heroicon-o-no-symbol')
                ->badge(User::where('status', 'suspended')->count()),

            NavigationItem::make()
                ->label('مرفوض') 
                ->group(static::getNavigationGroup())
                ->url(static::getUrl('index', ['status' => 'refused']))
                ->icon('heroicon-o-x-circle')
                ->badge(User::where('status', 'refused')->count()),
        ];
    }

}

<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\RelationManagers\RelationManager;

class FollowedByRelationManager extends RelationManager
{
    protected static string $relationship = 'followedBy';

    protected static ?string $relatedResource = UserResource::class;

    public static function getModelLabel(): string
    {
        return 'المتابعين';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المتابعين';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'المتابعين';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('الاسم الأول'),
                TextColumn::make('last_name')->label('الاسم الأخير'),
                TextColumn::make('email')->label('البريد الإلكتروني'),
                TextColumn::make('created_at')->label('تاريخ الانضمام')->dateTime(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

}

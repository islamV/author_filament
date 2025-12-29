<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class InvitationCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'ownedInvitationCodes';


    public static function getModelLabel(): string
    {
        return 'مستخدمين  كود الدعوة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'مستخدمين  كود الدعوة';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'مستخدمين  كود الدعوة';
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invitation_code')
                    ->label(__('filament.invitation_codes.code'))
                    ->required()
                    ->maxLength(50)
                    ->disabled(),
                Select::make('used_by')
                    ->label(__('filament.invitation_codes.used_by'))
                    ->relationship('usedBy', 'email')
                    ->searchable()
                    ->preload()
                    ->disabled(),
                TextInput::make('device_token')
                    ->label(__('filament.invitation_codes.device_token'))
                    ->maxLength(255)
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invitation_code')
            ->columns([
                Tables\Columns\TextColumn::make('invitation_code')
                    ->label(__('filament.invitation_codes.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usedBy.name')
                    ->label(__('filament.invitation_codes.used_by'))
                    ->getStateUsing(fn ($record) => $record->usedBy ? ($record->usedBy->first_name . ' ' . $record->usedBy->last_name) : __('filament.invitation_codes.not_used'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('usedBy.email')
                    ->label(__('filament.users.email'))
                    ->getStateUsing(fn ($record) => $record->usedBy ? $record->usedBy->email : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_token')
                    ->label(__('filament.invitation_codes.device_token'))
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.invitation_codes.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.invitation_codes.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('used')
                    ->label(__('filament.invitation_codes.used'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('used_by')),
                Tables\Filters\Filter::make('unused')
                    ->label(__('filament.invitation_codes.unused'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('used_by')),
            ])
            ->headerActions([])
            ->actions([
                // Read-only, no actions needed
            ]);
           
    }
}


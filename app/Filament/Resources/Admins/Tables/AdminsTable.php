<?php

namespace App\Filament\Resources\Admins\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;

class AdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.admins.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('filament.admins.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label(__('filament.admins.phone_number'))
                    ->searchable(),
                TextColumn::make('role.name')
                    ->label(__('filament.admins.role'))
                    ->badge()
                    ->searchable(),
                BadgeColumn::make('reviewed_books_count')
                    ->label('عدد المقالات التي راجعها')
                    ->getStateUsing(fn ($record) => $record->reviewedBooks()->count())
                    ->color('primary')
                    ->sortable(),    
                ImageColumn::make('image')
                    ->label(__('filament.admins.image')),
                TextColumn::make('created_at')
                    ->label(__('filament.admins.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.admins.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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


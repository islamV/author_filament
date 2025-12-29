<?php

namespace App\Filament\Resources\Ratings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RatingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.ratings.user'))
                    ->default(fn ($record) => $record->user ? ($record->user->first_name . ' ' . $record->user->last_name) : '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('book.title')
                    ->label(__('filament.ratings.book'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label(__('filament.ratings.rating'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('comment')
                    ->label(__('filament.ratings.comment'))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament.ratings.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.ratings.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label(__('filament.ratings.rating'))
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ]),
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


<?php

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.plans.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('filament.plans.price'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('max_downloads')
                    ->label('الحد الأقصى للتحميلات'),    
                TextColumn::make('description')
                    ->label(__('filament.plans.description'))
                    ->limit(50)
                    ->searchable()
                    ->html(),

                TextColumn::make('reward_points')
                    ->label('النقاط المكتسبة عند الاشتراك')
                    ->numeric()
                    ->sortable(),
    
                TextColumn::make('created_at')
                    ->label(__('filament.plans.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.plans.updated_at'))
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


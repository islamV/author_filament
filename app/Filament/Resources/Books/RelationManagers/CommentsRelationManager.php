<?php

namespace App\Filament\Resources\Books\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

  /*  public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('التعليق الرئيسي')
                    ->schema([
                        TextEntry::make('user.first_name')
                            ->label('المستخدم')
                            ->formatStateUsing(fn($state, $record) => $record->user->first_name . ' ' . $record->user->last_name),
                        
                        TextEntry::make('comment')
                            ->label('التعليق')
                            ->columnSpanFull(),
                        
                        TextEntry::make('created_at')
                            ->label('تاريخ النشر')
                            ->dateTime('Y-m-d H:i'),
                    ]),
                
                Section::make('الردود')
                    ->schema([
                        RepeatableEntry::make('replies')
                            ->label('')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('user.first_name')
                                            ->label('المستخدم')
                                            ->formatStateUsing(fn($state, $record) => $record->user->first_name . ' ' . $record->user->last_name),
                                        
                                        
                                        TextEntry::make('created_at')
                                            ->label('التاريخ')
                                            ->dateTime('Y-m-d H:i'),
                                    ]),
                                
                                TextEntry::make('comment')
                                    ->label('الرد')
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                    ])
                    ->hidden(fn($record) => $record->replies->isEmpty()),
            ]);
    }*/

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.first_name')
                    ->label('المستخدم')
                    ->formatStateUsing(fn($state, $record) => $record->user->first_name . ' ' . $record->user->last_name)
                    ->searchable(['user.first_name', 'user.last_name']),
                
                TextColumn::make('comment')
                    ->label('التعليق')
                    ->limit(100)
                    ->searchable()
                    ->wrap(),
                
                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
            ]);
    }
}

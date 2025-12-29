<?php

namespace App\Filament\Resources\Ratings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RatingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.ratings.basic_information'))
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('filament.ratings.user'))
                            ->getStateUsing(fn ($record) => $record->user ? ($record->user->first_name . ' ' . $record->user->last_name) : '-'),
                        TextEntry::make('book.title')
                            ->label(__('filament.ratings.book')),
                        TextEntry::make('rating')
                            ->label(__('filament.ratings.rating'))
                            ->numeric(),
                        TextEntry::make('comment')
                            ->label(__('filament.ratings.comment'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.ratings.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.ratings.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.ratings.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}


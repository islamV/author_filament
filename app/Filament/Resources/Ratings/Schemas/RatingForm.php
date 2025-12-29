<?php

namespace App\Filament\Resources\Ratings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RatingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.ratings.basic_information'))
                    ->schema([
                        Select::make('user_id')
                            ->label(__('filament.ratings.user'))
                            ->relationship('user', 'email')
                            ->searchable()
                            ->columns(2)
                            ->preload()
                            ->required(),
                        Select::make('book_id')
                            ->label(__('filament.ratings.book'))
                            ->relationship('book', 'title')
                            ->searchable()
                            ->preload()
                            ->columns(2)
                            ->required(),
                        TextInput::make('rating')
                            ->label(__('filament.ratings.rating'))
                            ->numeric()
                            ->columns(2)
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),
                        Textarea::make('comment')
                            ->label(__('filament.ratings.comment'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(2),
            ]);
    }
}


<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.categories.basic_information'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament.categories.name')),
                        ImageEntry::make('image')
                            ->label(__('filament.categories.image')),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.categories.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.categories.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.categories.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}

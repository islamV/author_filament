<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AdInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.ads.basic_information'))
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('filament.ads.title')),
                    ]),
                
                Section::make(__('filament.ads.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.ads.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.ads.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}

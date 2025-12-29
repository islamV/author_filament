<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.ads.basic_information'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament.ads.title'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

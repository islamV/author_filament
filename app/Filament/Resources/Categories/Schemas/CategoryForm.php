<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.categories.basic_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.categories.name'))
                            ->required(),
                        FileUpload::make('image')
                            ->label(__('filament.categories.image'))
                            ->image()
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

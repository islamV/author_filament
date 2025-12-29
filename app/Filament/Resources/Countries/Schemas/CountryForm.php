<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الدولة')->schema([
                        TextInput::make('name')
                            ->label('اسم الدولة')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label('رمز الدولة')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10),

                        TextInput::make('view_count')
                            ->label('عدد المشاهدات للأسعار')
                            ->required()
                            ->numeric()
                            ->default(1000)
                            ->minValue(1),

                        TextInput::make('price')
                            ->label('السعر')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                    /*   TextInput::make('desktop_price')
                            ->label('سعر الديسكتوب')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$'),  */ 
                ])
                ->columns(2)
                ->columnSpanFull(), 
            ]);
    }
}

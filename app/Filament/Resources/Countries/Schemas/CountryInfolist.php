<?php

namespace App\Filament\Resources\Countries\Schemas;

use App\Models\Country;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class CountryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الدولة')->schema([
                    TextEntry::make('name')
                        ->label('اسم الدولة'),


                    TextEntry::make('code')
                        ->label('رمز الدولة'),
                        
                    TextEntry::make('view_count')
                        ->label('عدد المشاهدات للأسعار'),
                        
                    TextEntry::make('price')
                        ->label('السعر'),
                            

                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء')
                        ->dateTime()
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->label('تاريخ التحديث')
                        ->dateTime()
                        ->placeholder('-'),

                    TextEntry::make('deleted_at')
                        ->label('تاريخ الحذف')
                        ->dateTime()
                        ->placeholder('-')
                        ->visible(fn (Country $record): bool => $record->trashed()),
                ])
                ->columns(2)
                ->columnSpanFull(),
                    
            ]);
    }
}

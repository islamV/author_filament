<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Models\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('اسم القسم'),
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
                    ->visible(fn (Section $record): bool => $record->trashed()),
            ]);
    }
}

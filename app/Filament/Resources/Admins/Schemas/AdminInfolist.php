<?php

namespace App\Filament\Resources\Admins\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdminInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.admins.basic_information'))
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('filament.admins.image'))
                            ->placeholder('-'),
                        TextEntry::make('name')
                            ->label(__('filament.admins.name')),
                        TextEntry::make('email')
                            ->label(__('filament.admins.email')),
                        TextEntry::make('phone_number')
                            ->label(__('filament.admins.phone_number'))
                            ->placeholder('-'),
                        TextEntry::make('role.name')
                            ->label(__('filament.admins.role'))
                            ->badge(),
                    ])
                    ->columns(2),
                
                Section::make(__('filament.admins.created_at'))
                    ->schema([

                        TextEntry::make('reviewed_articles_count')
                        ->label('عدد المقالات التي راجعها')
                        ->getStateUsing(fn ($record) => $record->reviewedBooks()->count())
                        ->badge()
                        ->color('primary'),
                        TextEntry::make('created_at')
                            ->label(__('filament.admins.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.admins.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}


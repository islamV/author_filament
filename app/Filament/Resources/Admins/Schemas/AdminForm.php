<?php

namespace App\Filament\Resources\Admins\Schemas;

use App\Models\Role;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.admins.basic_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.admins.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('filament.admins.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label(__('filament.admins.password'))
                            ->password()
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrated(fn ($state) => filled($state)),
                        TextInput::make('phone_number')
                            ->label(__('filament.admins.phone_number'))
                            ->tel(),
                        FileUpload::make('image')
                            ->label(__('filament.admins.image'))
                            ->image(),
                       Select::make('role_id')
                            ->label(__('filament.admins.role'))
                            ->options(Role::pluck('name', 'id')) 
                            ->searchable()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}


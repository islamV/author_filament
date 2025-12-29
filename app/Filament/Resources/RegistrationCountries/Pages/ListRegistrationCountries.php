<?php

namespace App\Filament\Resources\RegistrationCountries\Pages;

use App\Filament\Resources\RegistrationCountries\RegistrationCountryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationCountries extends ListRecords
{
    protected static string $resource = RegistrationCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\RegistrationCountries\Pages;

use App\Filament\Resources\RegistrationCountries\RegistrationCountryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRegistrationCountry extends ViewRecord
{
    protected static string $resource = RegistrationCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

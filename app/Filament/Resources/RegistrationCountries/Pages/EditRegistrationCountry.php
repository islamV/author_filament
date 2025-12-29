<?php

namespace App\Filament\Resources\RegistrationCountries\Pages;

use App\Filament\Resources\RegistrationCountries\RegistrationCountryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationCountry extends EditRecord
{
    protected static string $resource = RegistrationCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

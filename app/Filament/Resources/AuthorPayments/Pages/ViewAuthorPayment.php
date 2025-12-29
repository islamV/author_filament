<?php

namespace App\Filament\Resources\AuthorPayments\Pages;

use App\Filament\Resources\AuthorPayments\AuthorPaymentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAuthorPayment extends ViewRecord
{
    protected static string $resource = AuthorPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // EditAction::make(),
        ];
    }
}

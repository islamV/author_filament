<?php

namespace App\Filament\Resources\AuthorPayments\Pages;

use App\Filament\Resources\AuthorPayments\AuthorPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthorPayments extends ListRecords
{
    protected static string $resource = AuthorPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // CreateAction::make(),
        ];
    }
}

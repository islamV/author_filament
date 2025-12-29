<?php

namespace App\Filament\Resources\AuthorRequestPayments\Pages;

use App\Filament\Resources\AuthorRequestPayments\AuthorRequestPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorRequestPayment extends EditRecord
{
    protected static string $resource = AuthorRequestPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

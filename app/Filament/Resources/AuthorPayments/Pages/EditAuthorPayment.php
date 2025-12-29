<?php

namespace App\Filament\Resources\AuthorPayments\Pages;

use App\Filament\Resources\AuthorPayments\AuthorPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorPayment extends EditRecord
{
    protected static string $resource = AuthorPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

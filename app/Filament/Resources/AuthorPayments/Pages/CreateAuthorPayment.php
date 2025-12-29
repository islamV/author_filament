<?php

namespace App\Filament\Resources\AuthorPayments\Pages;

use App\Filament\Resources\AuthorPayments\AuthorPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthorPayment extends CreateRecord
{
    protected static string $resource = AuthorPaymentResource::class;
}

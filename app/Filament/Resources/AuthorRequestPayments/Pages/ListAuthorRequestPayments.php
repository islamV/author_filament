<?php

namespace App\Filament\Resources\AuthorRequestPayments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AuthorRequestPayments\AuthorRequestPaymentResource;

class ListAuthorRequestPayments extends ListRecords
{
    protected static string $resource = AuthorRequestPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(fn () => \App\Models\AuthorRequestPayment::count()),

            'pending' => Tab::make('قيد الانتظار')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn () => \App\Models\AuthorRequestPayment::where('status', 'pending')->count()),

            'approved' => Tab::make('تمت الموافقة')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(fn () => \App\Models\AuthorRequestPayment::where('status', 'approved')->count()),

            'paid' => Tab::make('تم الدفع')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid'))
                ->badge(fn () => \App\Models\AuthorRequestPayment::where('status', 'paid')->count()),

            'rejected' => Tab::make('مرفوض')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(fn () => \App\Models\AuthorRequestPayment::where('status', 'rejected')->count()),
        ];
    }
}

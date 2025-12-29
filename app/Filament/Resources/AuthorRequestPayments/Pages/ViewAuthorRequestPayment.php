<?php

namespace App\Filament\Resources\AuthorRequestPayments\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AuthorRequestPayments\AuthorRequestPaymentResource;

class ViewAuthorRequestPayment extends ViewRecord
{
    protected static string $resource = AuthorRequestPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('update_status')
                ->label('تحديث الحالة')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->label('الحالة الجديدة')
                        ->options([
                            'pending' => 'قيد الانتظار',
                            'approved' => 'تمت الموافقة',
                            'rejected' => 'مرفوض',
                        ])
                        ->required(),
                ])
                ->action(function ($record, array $data): void {
                    $record->update([
                        'status' => $data['status'],
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('تم تحديث حالة طلب الدفع بنجاح')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->color('primary'),

        ];
    }
}

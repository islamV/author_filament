<?php

namespace App\Filament\Resources\Books\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\RichEditor;
use App\Services\FirebaseNotificationService;
use App\Filament\Resources\Books\BookResource;


class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
           Action::make('accept_action')
            ->label('قبول')
            ->color('success')
            ->action(function ($record) {
                $record->status = 'accepted';
                $record->reviewer_id = Auth::id();
                $record->refusal_reason = null;
                $record->save();


                if ($record->user_id) {
                    app(FirebaseNotificationService::class)
                        ->sendCustomNotification(
                            'تم قبول كتابك',
                            'تمت الموافقة على كتابك ويمكنك الآن الاطلاع عليه.',
                            [$record->user_id],
                            false,
                            [
                                'type' => 'book_accepted',
                                'book_id' => (string) $record->id,
                            ]
                        );
                }

                $followers = $record->user->followedBy()->pluck('users.id')->toArray();
                    if (!empty($followers)) {
                        app(FirebaseNotificationService::class)
                            ->sendCustomNotification(
                                'كتاب جديد من المؤلف الذي تتابعه',
                                "{$record->user->getNameAttribute()} نشر كتاب جديد: {$record->title}",
                                $followers,
                                false,
                                [
                                    'type' => 'new_book',
                                    'book_id' => (string) $record->id,
                                    'author_id' => (string) $record->user->id,
                                ]
                            );
                }

                Notification::make()
                    ->success()
                    ->title('تم قبول الكتاب')
                    ->send();
            })
            ->visible(fn ($record) => $record->status === 'refused'),

        Action::make('refuse_action')
            ->label('رفض')
            ->color('danger')
            ->form([
                RichEditor::make('refusal_reason')
                    ->label('سبب الرفض')
                    ->required(),
            ])
            ->action(function ($record, array $data) {
                $record->status = 'refused';
                $record->reviewer_id = Auth::id();
                $record->refusal_reason = $data['refusal_reason'];
                $record->save();

                Notification::make()
                    ->success()
                    ->title('تم تحديث حالة الكتاب إلى "رفض"')
                    ->send();
            })
            ->visible(fn ($record) => $record->status === 'accepted'),    

                    
            Action::make('accept')
                ->label('انهاء المراجعة - جاهز للنشر')
                ->color('success')
                ->action(function ($record) {
                    $record->status = 'accepted';
                    $record->reviewer_id = Auth::id();
                    $record->refusal_reason = null;
                    $record->save();


                    app(FirebaseNotificationService::class)
                        ->sendCustomNotification(
                            'تم قبول كتابك',
                            'تمت الموافقة على كتابك ويمكنك الآن الاطلاع عليه.',
                            [$record->user_id],
                            false,
                            [
                            'type' => 'book_accepted',
                            'book_id' => (string) $record->id,
                            ]
                    );

                    $followers = $record->user->followedBy()->pluck('users.id')->toArray();
                        if (!empty($followers)) {
                            app(FirebaseNotificationService::class)
                                ->sendCustomNotification(
                                    'كتاب جديد من المؤلف الذي تتابعه',
                                    "{$record->user->getNameAttribute()} نشر كتاب جديد: {$record->title}",
                                    $followers,
                                    false,
                                    [
                                        'type' => 'new_book',
                                        'book_id' => (string) $record->id,
                                        'author_id' => (string) $record->user->id,
                                    ]
                                );
                    }

                    Notification::make()
                        ->success()
                        ->title('تم قبول الكتاب مباشرة')
                        ->send();
                })
                ->visible(fn ($record) => $record->status === 'pending'),

            Action::make('refuse')
                ->label('انهاء المراجعة - يحتاج تعديل')
                ->color('danger')
                ->form([
                    RichEditor::make('refusal_reason')
                        ->label('سبب الرفض')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->status = 'refused';
                    $record->reviewer_id = Auth::id();
                    $record->refusal_reason = $data['refusal_reason'];
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title('تم تحديث حالة الكتاب إلى "يحتاج تعديل"')
                        ->send();
                })->visible(fn ($record) => $record->status === 'pending'),
            ];
    }
}

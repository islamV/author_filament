<?php

namespace App\Filament\Resources\Books\Pages;

use App\Models\BookPage;
use App\Models\book_parts;
use Filament\Actions\Action;
use App\Helpers\ContentHelper;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\RichEditor;
use App\Services\FirebaseNotificationService;
use App\Filament\Resources\Books\BookResource;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
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

    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     // Load book_parts with their pages
    //     $book = $this->record;
    //     $bookParts = $book->book_parts()->with('bookPages')->get();

    //     // Format data for Repeater
    //     $data['book_parts'] = $bookParts->map(function ($part) {
    //         // Combine all pages content into one
    //         $content = $part->bookPages->sortBy('page_number')
    //             ->pluck('content')
    //             ->implode('');

    //         return [
    //             'chapter' => $part->chapter,
    //             'content' => $content,
    //             'is_published' => $part->is_published,
    //         ];
    //     })->toArray();

    //     return $data;
    // }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     // Extract book_parts from data
    //     $bookParts = $data['book_parts'] ?? [];
    //     unset($data['book_parts']);

    //     return $data;
    // }

    // protected function afterSave(): void
    // {
    //     $book = $this->record;
    //     $bookParts = $this->form->getState()['book_parts'] ?? [];

    //     // Delete existing book parts and pages
    //     $existingParts = $book->book_parts;
    //     foreach ($existingParts as $part) {
    //         $part->bookPages()->delete();
    //         $part->delete();
    //     }

    //     // Create new book parts and pages
    //     foreach ($bookParts as $partData) {
    //         // Create book part
    //         $bookPart = book_parts::create([
    //             'book_id' => $book->id,
    //             'chapter' => $partData['chapter'],
    //             'is_published' => $partData['is_published'] ?? true,
    //         ]);

    //         // Split content if it's too large
    //         $content = $partData['content'] ?? '';
    //         if (!empty($content)) {
    //             $contentChunks = ContentHelper::splitContent($content);
                
    //             // Create pages for each chunk
    //             foreach ($contentChunks as $index => $chunk) {
    //                 BookPage::create([
    //                     'book_part_id' => $bookPart->id,
    //                     'page_number' => $index + 1,
    //                     'content' => $chunk,
    //                 ]);
    //             }
    //         }
    //     }
    // }
}

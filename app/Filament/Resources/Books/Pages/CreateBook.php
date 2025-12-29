<?php

namespace App\Filament\Resources\Books\Pages;

use App\Filament\Resources\Books\BookResource;
use App\Helpers\ContentHelper;
use App\Models\BookPage;
use App\Models\book_parts;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // // Extract book_parts from data
    //     // $bookParts = $data['book_parts'] ?? [];
    //     // unset($data['book_parts']);

    //     // return $data;
    // }

    // protected function afterCreate(): void
    // {
    //     $book = $this->record;
    //     $bookParts = $this->form->getState()['book_parts'] ?? [];

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

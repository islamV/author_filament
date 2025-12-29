<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                ImageColumn::make('image')
                    ->label(__('filament.books.image')),
             
                TextColumn::make('title')
                    ->label(__('filament.books.title'))
                    ->searchable(),
                TextColumn::make('user.last_name')
                    ->label(__('filament.books.user_id'))
                     ->sortable(),
                TextColumn::make('category.name')
                    ->label(__('filament.books.category_id'))
                     ->sortable(),

                BadgeColumn::make('publish_status')
                    ->label('حالة المنشور')
                    ->colors([
                        'primary' => 'published',
                        'secondary' => 'draft',
                        'warning' => 'scheduled',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'published' => 'منشور',
                        'draft' => 'مسودة',
                        'scheduled' => 'جدولة',
                        default => $state,
                    })
                    ->sortable(),

                ToggleColumn::make('is_popular')
                    ->label(__('filament.books.is_popular')),

                ToggleColumn::make('is_featured')
                    ->label(__('filament.books.is_featured')),

                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'success' => 'accepted',
                        'danger' => 'refused',
                        'secondary' => 'pending',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'accepted' => 'تم القبول',
                        'refused' => 'تم الرفض',
                        'pending' => 'قيد المراجعة',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),


                TextColumn::make('created_at')
                    ->label(__('filament.books.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.books.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('filament.books.category_id'))
                    ->relationship('category', 'name', fn ($query) => $query->whereNotNull('name')->orderBy('name')),
                
                SelectFilter::make('user_id')
                    ->label(__('filament.books.user_id'))
                    ->relationship(
                        'user',
                        'last_name',
                        fn ($query) => $query
                            ->whereNotNull('last_name')
                            ->where('last_name', '!=', '')
                            ->orderBy('last_name')
                    )
                    ->searchable()
                    ->preload(),
                
                Filter::make('publish_status')
                    ->label('حالة النشر')
                    ->form([
                        Radio::make('publish_status')
                            ->options([
                                'published' => 'منشور',
                                'draft' => 'مسودة',
                                'scheduled' => 'مجدول',
                            ])
                            ->inline()
                            ->label('حالة النشر'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['publish_status']),
                            fn (Builder $query) => $query->where('publish_status', $data['publish_status'])
                        );
                    }),
                
                Filter::make('is_popular')
                    ->label(__('filament.books.is_popular'))
                    ->form([
                        \Filament\Forms\Components\Radio::make('is_popular')
                            ->options([
                                1 => __('filament.actions.yes'),
                                0 => __('filament.actions.no'),
                            ])
                            ->label(__('filament.books.is_popular'))
                            ->inline(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['is_popular']),
                            fn (Builder $query): Builder => $query->where('is_popular', (int) $data['is_popular']),
                        );
                    }),
                
                Filter::make('is_featured')
                    ->label(__('filament.books.is_featured'))
                    ->form([
                        \Filament\Forms\Components\Radio::make('is_featured')
                            ->options([
                                1 => __('filament.actions.yes'),
                                0 => __('filament.actions.no'),
                            ])
                            ->label(__('filament.books.is_featured'))
                            ->inline(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['is_featured']),
                            fn (Builder $query): Builder => $query->where('is_featured', (int) $data['is_featured']),
                        );
                    }),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
              /*  Action::make('accept')
                    ->label('قبول')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record, $data, $form) {
                        $record->status = 'accepted';
                        $record->refusal_reason = null;
                        $record->save();
                    }),

                Action::make('refuse')
                    ->label('رفض')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->form([
                        RichEditor::make('reason')
                            ->label('سبب الرفض')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = 'refused';
                        $record->refusal_reason = $data['reason'];
                        $record->save();
                    }),*/


                  Action::make('review')
                    ->label('مراجعة')
                    ->color('primary')
                    ->url(fn ($record) => \App\Filament\Resources\Books\BookResource::getUrl('edit', ['record' => $record->id]))
                    ->openUrlInNewTab()
                    ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

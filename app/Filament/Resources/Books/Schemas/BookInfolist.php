<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('معلومات الكتاب والفصول')
                            ->schema([
                                Section::make('معلومات الكتاب')
                                    ->schema([
                                        TextEntry::make('title')->label('عنوان الكتاب'),
                                        TextEntry::make('user.name')->label('المؤلف'),
                                        TextEntry::make('reviewer.name')
                                            ->label('المراجع')
                                            ->visible(fn ($record) => in_array($record->status, ['accepted', 'refused'])),
                                        TextEntry::make('description')
                                            ->label('وصف الكتاب')
                                            ->columnSpanFull()
                                            ->html(),
                                        ImageEntry::make('image')
                                            ->label('صورة الغلاف')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->height(150),
                                        TextEntry::make('pdf_path')->label('ملف PDF')->placeholder('-'),
                                        TextEntry::make('category.name')->label('التصنيف'),
                                        TextEntry::make('section.name')->label('القسم'),
                                        IconEntry::make('publish_status')
                                            ->label('حالة النشر')
                                            ->icon(fn ($state): string => match ($state) {
                                                'published' => 'heroicon-o-check-circle',  
                                                'draft' => 'heroicon-o-document-text',     
                                                'scheduled' => 'heroicon-o-clock',        
                                                default => 'heroicon-o-question-mark-circle', 
                                            })
                                            ->color(fn ($state): string => match ($state) {
                                                'published' => 'success',   
                                                'draft' => 'gray',          
                                                'scheduled' => 'warning',   
                                                default => 'danger',        
                                            })
                                            ->formatStateUsing(fn ($state): string => match ($state) {
                                                'published' => 'منشور',
                                                'draft' => 'مسودة',
                                                'scheduled' => 'مجدول',
                                                default => 'غير معروف',
                                            }),
                                        IconEntry::make('is_popular')->label('شائع')->boolean(),
                                        IconEntry::make('is_featured')->label('مميز')->boolean(),
                                        TextEntry::make('created_at')->label('تاريخ الإنشاء')->dateTime()->placeholder('-'),
                                        TextEntry::make('updated_at')->label('تاريخ التحديث')->dateTime()->placeholder('-'),
                                    ])
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->columns(3),

                                Section::make('فصول الكتاب')
                                    ->schema([
                                        RepeatableEntry::make('book_parts')
                                            ->label('الفصول')
                                            ->schema([
                                                TextEntry::make('chapter')->label('اسم الفصل')->columnSpanFull(),
                                                TextEntry::make('content')->label('محتوى الفصل')->columnSpanFull()->html(),
                                                IconEntry::make('is_published')->label('منشور')->boolean(),
                                                IconEntry::make('is_reviewed')->label('تمت المراجعة')->boolean(),
                                            ])
                                            ->columns(2),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpanFull(),

                        Tab::make('سبب الرفض')
                            ->schema([
                                Section::make('سبب الرفض')
                                    ->schema([
                                        TextEntry::make('refusal_reason')
                                            ->label('سبب الرفض')
                                            ->html(),
                                    ])
                                    ->collapsible(),
                            ])
                            ->visible(fn ($record) => $record->status === 'refused')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

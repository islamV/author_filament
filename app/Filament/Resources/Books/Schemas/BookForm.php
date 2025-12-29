<?php

namespace App\Filament\Resources\Books\Schemas;

use App\Models\User;
use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use App\Models\Section as SectionModel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\DateTimePicker;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('معلومات الكتاب')
                            ->schema([
                                TextInput::make('title')
                                    ->label('عنوان الكتاب')
                                    ->required()
                                    ->disabled(fn ($record) => $record !== null), 

                                Select::make('user_id')
                                    ->label('المؤلف')
                                    ->relationship('user', 'first_name')
                                    ->required()
                                    ->disabled(fn ($record) => $record !== null),

                                RichEditor::make('description')
                                    ->label('وصف الكتاب')
                                    ->required()
                                    ->columnSpanFull(), 

                                FileUpload::make('image')
                                    ->label('صورة الغلاف')
                                    ->image()
                                    ->disk('public')
                                    ->directory('Books/Images')
                                    ->required()
                                    ->disabled(fn ($record) => $record !== null),

                                FileUpload::make('pdf_path')
                                    ->label('ملف PDF')
                                    ->disk('public')
                                    ->directory('Books/PDFs')
                                    ->nullable()
                                    ->disabled(fn ($record) => $record !== null),

                                Select::make('category_id')
                                    ->label('التصنيف')
                                    ->relationship('category', 'name')
                                    ->required(),

                                Select::make('section_id')
                                    ->label('القسم')
                                    ->relationship('section', 'name')
                                    ->required(),

                                Radio::make('publish_status')
                                    ->label('حالة المنشور')
                                    ->options([
                                        'draft' => 'مسودة',
                                        'published' => 'منشور',
                                        'scheduled' => 'جدولة',
                                    ])
                                    ->default('published')
                                    ->inline()
                                    ->reactive(),

                                DateTimePicker::make('scheduled_until')
                                    ->label('تاريخ النشر المجدول')
                                    ->default(now())
                                    ->hidden(fn ($get) => $get('publish_status') !== 'scheduled'),

                                Toggle::make('is_popular')
                                    ->label('شائع')
                                    ->required(),

                                Toggle::make('is_featured')
                                    ->label('مميز')
                                    ->required(),
                            ])
                            ->columns(2),

                        Tab::make('الفصول والمحتوى')
                            ->schema([
                                Repeater::make('book_parts')
                                    ->relationship('book_parts')
                                    ->label('الفصول')
                                    ->schema([
                                        Section::make(fn (array $state): ?string => 'الفصل: ' . ($state['chapter'] ?? 'فصل جديد'))
                                            ->schema([
                                                TextInput::make('chapter')
                                                    ->label('اسم الفصل')
                                                    ->required()
                                                    ->maxLength(255)
                                                   // ->disabled(fn ($record) => $record !== null) 
                                                    ->columnSpanFull(),

                                                RichEditor::make('content')
                                                    ->label('محتوى الفصل')
                                                    ->required()
                                                    ->columnSpanFull(), 

                                                Toggle::make('is_published')
                                                    ->label('منشور')
                                                    ->default(true),
                                                   // ->disabled(fn ($record) => $record !== null),

                                                Toggle::make('is_reviewed')  
                                                    ->label('تمت المراجعة')
                                                    ->default(false),    
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->collapsed(false)
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('إضافة فصل')
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['chapter'] ?? 'فصل جديد')
                                    ->columnSpanFull()
                                    ->grid(1),
                            ]),

                       Tab::make('تحديث الحالة')
                        ->schema([
                            Radio::make('status')
                                ->label('حالة الكتاب')
                                ->options([
                                    'pending'   => 'قيد المراجعة',
                                    'accepted' => 'مقبول',
                                    'refused' => 'مرفوض',
                                ])
                                ->inline()
                                ->required()
                                ->reactive(),

                            RichEditor::make('refusal_reason')
                                ->label('سبب الرفض')
                                ->columnSpanFull()
                                ->visible(fn ($get) => $get('status') === 'refused'),
                        ])
                        ->columns(1),

                    ])
                    ->columnSpanFull(),
            ]);
    }
}

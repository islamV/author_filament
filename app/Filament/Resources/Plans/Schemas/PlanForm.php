<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم الباقة')
                            ->columns(2)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->label('السعر')
                            ->required()
                            ->columns(2)
                            ->maxLength(255),

                         TextInput::make('duration')
                            ->label('مدة الاشتراك (أيام)')
                            ->required()
                            ->columns(2)
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('discount_value')
                            ->label('قيمة الخصم')
                            ->columns(2)
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Select::make('discount_type')
                            ->label('نوع الخصم')
                            ->options([
                                'percentage' => 'نسبة مئوية',
                                'fixed' => 'مبلغ ثابت',
                            ])
                            ->default('percentage')
                            ->columns(2),    

                        TextInput::make('max_downloads')
                            ->label('الحد الأقصى للتحميلات')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('reward_points')
                            ->label('عدد النقاط المكتسبة عند الاشتراك في هذه الباقة')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->helperText('اتركه فارغًا إذا كانت الباقة لا تمنح نقاط'),
                            

                        RichEditor::make('description')
                            ->label('الوصف')
                            ->required()
                            ->columnSpanFull(),
                        Repeater::make('features')
                            ->label('المميزات')
                            ->required()
                            ->schema([
                                TextInput::make('feature')->label('الميزة')->required(),
                            ])
                            ->default(fn ($record) => $record?->features ?? [['feature' => '']])
                            ->columnSpanFull(),

                    ])->columnSpanFull(),
            ]);
    }
}


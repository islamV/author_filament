<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.plans.basic_information'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament.plans.name')),
                        TextEntry::make('price')
                            ->label(__('filament.plans.price')),
                        TextEntry::make('duration')
                            ->label('مدة الاشتراك (أيام)'), 

                        TextEntry::make('discount_value')
                            ->label('قيمة الخصم'), 

                        TextEntry::make('discount_type')
                            ->label('نوع الخصم'),
    
                        TextEntry::make('max_downloads')
                            ->label('الحد الأقصى للتحميلات'),    
                        TextEntry::make('description')
                            ->label(__('filament.plans.description'))
                            ->columnSpanFull()
                            ->html(),


                    
                        TextEntry::make('reward_points')
                            ->label('عدد النقاط التي يحصل عليها المستخدم عند الاشتراك في هذه الباقة')
                            ->numeric()
                            ->placeholder('0'),
                           
        
                        TextEntry::make('features')
                            ->label('المميزات')
                            ->formatStateUsing(function ($state) {
                                if (blank($state)) {
                                    return 'لا توجد مميزات';
                                }

                                if (is_array($state)) {
                                   
                                    return implode(', ', $state);
                                }

                                return $state;
                            })
                            ->columnSpanFull(),


 

                    ])
                    ->columns(2),
                
                Section::make(__('filament.plans.created_at'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament.plans.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament.plans.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}


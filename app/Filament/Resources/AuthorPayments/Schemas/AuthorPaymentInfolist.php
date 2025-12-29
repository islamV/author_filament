<?php

namespace App\Filament\Resources\AuthorPayments\Schemas;

use App\Models\Setting;
use Filament\Schemas\Schema;
use App\Models\InvitationCode;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class AuthorPaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $settings = Setting::first();
        $moneyUses = $settings?->invitation_money_uses ?? 1;
        $rewardMoney = $settings?->invitation_reward_money ?? 0;

        return $schema
            ->components([
                Section::make('تفاصيل الأرباح')
                    ->schema([
                        TextEntry::make('name')
                            ->label('المؤلف'),

                        TextEntry::make('total_views')
                            ->label('إجمالي المشاهدات')
                            ->getStateUsing(fn($record) => $record->totalViews()),

                        TextEntry::make('view_revenue')
                            ->label('أرباح المشاهدات')
                            ->getStateUsing(fn($record) => $record->calculateEarnings()),

                        TextEntry::make('invitation_count')
                            ->label('عدد المستخدمين الذين استخدموا الكود')
                            ->getStateUsing(function($record) {
                                return InvitationCode::where('user_id', $record->id)
                                    ->whereNotNull('used_by')
                                    ->count();
                            }),

                        TextEntry::make('invitation_revenue')
                            ->label('أرباح الدعوات')
                            ->getStateUsing(function($record) use ($moneyUses, $rewardMoney) {
                                $invitationCount = InvitationCode::where('user_id', $record->id)
                                    ->whereNotNull('used_by')
                                    ->count();
                                
                                return floor($invitationCount / $moneyUses) * $rewardMoney;
                            }),

                        TextEntry::make('total_author_revenue')
                            ->label('إجمالي الأرباح')
                            ->getStateUsing(function($record) use ($moneyUses, $rewardMoney) {
                                $invitationCount = InvitationCode::where('user_id', $record->id)
                                    ->whereNotNull('used_by')
                                    ->count();
                                
                                return $record->calculateEarnings() + 
                                       floor($invitationCount / $moneyUses) * $rewardMoney;
                            }),
                    ])->columns(2)
                    ->columnspanfull()
            ]);
    }
}

<?php

namespace App\Filament\Resources\AuthorPayments\Tables;

use App\Models\User;
use App\Models\Setting;
use App\Models\InvitationCode;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class AuthorPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('المؤلف'),

                TextColumn::make('total_views')
                    ->label('إجمالي المشاهدات')
                    ->getStateUsing(fn($record) => $record->totalViews()),

                TextColumn::make('view_revenue')
                    ->label('أرباح المشاهدات')
                    ->getStateUsing(fn($record) => $record->calculateEarnings()),

                TextColumn::make('invitation_count')
                    ->label('عدد المستخدمين الذين استخدموا الكود')
                    ->getStateUsing(fn($record) => InvitationCode::where('user_id', $record->id)
                        ->whereNotNull('used_by')
                        ->count()),

                TextColumn::make('invitation_revenue')
                    ->label('أرباح الدعوات')
                    ->getStateUsing(function ($record) {
                        $settings = Setting::first();
                        $moneyUses = $settings?->invitation_money_uses ?? 1;
                        $rewardMoney = $settings?->invitation_reward_money ?? 0;

                        $count = InvitationCode::where('user_id', $record->id)
                            ->whereNotNull('used_by')
                            ->count();

                        return floor($count / $moneyUses) * $rewardMoney;
                    }),

                TextColumn::make('total_author_revenue')
                    ->label('إجمالي الأرباح')
                    ->getStateUsing(function ($record) {
                        $settings = Setting::first();
                        $moneyUses = $settings?->invitation_money_uses ?? 1;
                        $rewardMoney = $settings?->invitation_reward_money ?? 0;

                        $totalInvitationUses = InvitationCode::where('user_id', $record->id)
                            ->whereNotNull('used_by')
                            ->count();

                        return $record->calculateEarnings() +
                               floor($totalInvitationUses / $moneyUses) * $rewardMoney;
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
               // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return User::query()->whereIn('role_id', [2, 3]);
    }

}

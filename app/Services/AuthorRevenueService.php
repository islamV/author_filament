<?php

namespace App\Services;

use App\Models\InvitationCode;
use App\Models\Setting;
use App\Models\AuthorRequestPayment;

class AuthorRevenueService
{
    public function calculate($author)
    {
        $settings = Setting::first();
        $moneyUses   = $settings?->invitation_money_uses ?? 1;
        $rewardMoney = $settings?->invitation_reward_money ?? 0;

        $totalViewsRevenue = $author->calculateEarnings();

        $totalInvitationUses = InvitationCode::where('user_id', $author->id)
            ->whereNotNull('used_by')
            ->count();
        $totalInvitationRevenue = floor($totalInvitationUses / $moneyUses) * $rewardMoney;

        $totalRevenue = $totalViewsRevenue + $totalInvitationRevenue;

        $totalPaid = AuthorRequestPayment::where('user_id', $author->id)
            ->where('status', 'paid')
            ->sum('requested_amount');

        $availableBalance = $totalRevenue - $totalPaid;

      //  dd($totalRevenue , $totalPaid);

        return [
            'totalRevenue' => $totalRevenue,
            'totalPaid' => $totalPaid,
            'availableBalance' => $availableBalance,
        ];
    }
}

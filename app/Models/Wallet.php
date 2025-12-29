<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance' , 'total_earned'];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreateByUser($user)
    {
        $wallet = $user->wallet;
        if (! $wallet) {
            $wallet = self::create([
                'user_id' => $user->id,
                'balance' => 0,
                'total_earned' => 0,
            ]);
        }
        return $wallet;
    }

    public function deduct($amount)
    {
        $this->balance = max(0, $this->balance - $amount);
        $this->save();
    }

}

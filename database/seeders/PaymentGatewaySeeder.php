<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::create([
            'id' => 1,
            "payment_gateway" => "instaPay",
        ]);
        PaymentGateway::create([
            'id' => 2,
            "payment_gateway" => "vodafone",
        ]);
        PaymentGateway::create([
            'id' => 3,
            "payment_gateway" => "banking",
        ]);
        PaymentGateway::create([
            'id' => 4,
            "payment_gateway" => "payoneer",
        ]);
        PaymentGateway::create([
            'id' => 5,
            "payment_gateway" => "binance",
        ]);
        PaymentGateway::create([
            'id' => 6,
            "payment_gateway" => "perfect-money",
        ]);
        PaymentGateway::create([
            'id' => 7,
            "payment_gateway" => "payeer",
        ]);
        PaymentGateway::create([
            'id' => 8,
            "payment_gateway" => "orange",
        ]);
        PaymentGateway::create([
            'id' => 9,
            "payment_gateway" => "etisalat",
        ]);
    }
}

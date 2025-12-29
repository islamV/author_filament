<?php

namespace Database\Seeders;

use App\Models\AuthorRequestPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorRequestPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('author_request_payments')->insert([
            [
                'requested_amount' => 500.00,
                'user_id' => 1, // Assuming user_id 1 exists in the users table
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'phone' => '12345678901',
                'beneficiary_name' => 'John Doe',
                'bank_name' => 'Example Bank',
                'iban' => 'DE12345678901234567890',
                'wallet_id' => null,
                'wallet_name' => null,
                'email_binance' => null,
                'beneficiary_address' => '123 Example Street, Example City',
                'swift_bio_code' => 'EXPLDEFF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'requested_amount' => 200.75,
                'user_id' => 2, // Assuming user_id 2 exists in the users table
                'status' => 'approved',
                'payment_method' => 'crypto_wallet',
                'phone' => null,
                'beneficiary_name' => null,
                'bank_name' => null,
                'iban' => null,
                'wallet_id' => 'ABC123XYZ',
                'wallet_name' => 'Bitcoin Wallet',
                'email_binance' => 'john.doe@example.com',
                'beneficiary_address' => null,
                'swift_bio_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'requested_amount' => 1000.00,
                'user_id' => 3, // Assuming user_id 3 exists in the users table
                'status' => 'rejected',
                'payment_method' => 'paypal',
                'phone' => null,
                'beneficiary_name' => 'Jane Smith',
                'bank_name' => null,
                'iban' => null,
                'wallet_id' => null,
                'wallet_name' => null,
                'email_binance' => null,
                'beneficiary_address' => null,
                'swift_bio_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminPaymentDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ChatSeeder::class);
        $this->call(UserSeeder::class);
//        User::factory()->count(5000)->create();
        $this->call(CategorySeeder::class);
        // $this->call(BookSeeder::class);
        // $this->call(book_partsSeeder::class);
        $this->call(AdSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(AuthorRequestPaymentSeeder::class);
        $this->call(SectionSeeder::class);
        $this->call(PlanSeeder::class);
        AdminPaymentDetail::create([
            'phone_number' => '01000000000',
            'bank_name' => 'CIB',
            'account_number' => '123456789',
            'receiver_name' => 'Ahmed',
        ]);

        Admin::create([
            'name'      => 'Admin',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('123456789'),
        
        ]);

    }
}

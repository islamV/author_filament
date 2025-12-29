<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'first_name' => 'admin',
            'last_name' => 'dash',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e0C9qORQR-Gy664eEJKhdH:APA91bG0MhGkG1xhjXG-7h0QR_OnePXAXecXHCFQ57Jzu5vA-vKMVnBksN_g5LNFcz5tcM-JSRAatHfcMIWT1rX2badUDeY3n4HKXLSQzGtrN6Stha_ozg0',
            'role_id' => 1,
        ]);

        User::create([
            'id' => 2,
            'first_name' => 'author',
            'last_name' => 'dash',
            'email' => 'author@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e0C9qORQR-Gy664eEJKhdH:APA91bG0MhGkG1xhjXG-7h0QR_OnePXAXecXHCFQ57Jzu5vA-vKMVnBksN_g5LNFcz5tcM-JSRAatHfcMIWT1rX2badUDeY3n4HKXLSQzGtrN6Stha_ozg0',
            'role_id' => 2,
        ]);

        User::create([
            'id' => 3,
            'first_name' => 'ahmed',
            'last_name' => 'khaled',
            'email' => 'ahmed@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e0C9qORQR-Gy664eEJKhdH:APA91bG0MhGkG1xhjXG-7h0QR_OnePXAXecXHCFQ57Jzu5vA-vKMVnBksN_g5LNFcz5tcM-JSRAatHfcMIWT1rX2badUDeY3n4HKXLSQzGtrN6Stha_ozg0',
            'role_id' => 4,
        ]);

        User::create([
            'id' => 4,
            'first_name' => 'abdo',
            'last_name' => 'khaled',
            'email' => 'abdo@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e5vQo22ARsOXyt0Bkt-MWI:APA91bHgkPPfhg9ZHxquDEF2zcZeIOcuqHUSwotNtSHxDAU1JuIQSHlXgsESmEzyU-6MZOHj2qo26O-kdBu8dD_0ZItrI0SgFRcwuiX1jDpz7rbH9FayhaI',
            'role_id' => 3,
        ]);

        User::create([
            'id' => 5,
            'first_name' => 'ziad',
            'last_name' => 'el sayed',
            'email' => 'ziad@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'pending',
            'device_token' => 'e0C9qORQR-Gy664eEJKhdH:APA91bG0MhGkG1xhjXG-7h0QR_OnePXAXecXHCFQ57Jzu5vA-vKMVnBksN_g5LNFcz5tcM-JSRAatHfcMIWT1rX2badUDeY3n4HKXLSQzGtrN6Stha_ozg0',
            'role_id' => 4,
        ]);

        User::create([
            'id' => 6,
            'first_name' => 'mohamed',
            'last_name' => 'khaled',
            'email' => 'mohamed@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e5vQo22ARsOXyt0Bkt-MWI:APA91bHgkPPfhg9ZHxquDEF2zcZeIOcuqHUSwotNtSHxDAU1JuIQSHlXgsESmEzyU-6MZOHj2qo26O-kdBu8dD_0ZItrI0SgFRcwuiX1jDpz7rbH9FayhaI',
            'role_id' => 3,
        ]);

        User::create([
            'id' => 7,
            'first_name' => 'ali',
            'last_name' => 'khaled',
            'email' => 'ali@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e5vQo22ARsOXyt0Bkt-MWI:APA91bHgkPPfhg9ZHxquDEF2zcZeIOcuqHUSwotNtSHxDAU1JuIQSHlXgsESmEzyU-6MZOHj2qo26O-kdBu8dD_0ZItrI0SgFRcwuiX1jDpz7rbH9FayhaI',
            'role_id' => 2,
        ]);

        User::create([
            'id' => 8,
            'first_name' => 'amr',
            'last_name' => 'khaled',
            'email' => 'amr@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e5vQo22ARsOXyt0Bkt-MWI:APA91bHgkPPfhg9ZHxquDEF2zcZeIOcuqHUSwotNtSHxDAU1JuIQSHlXgsESmEzyU-6MZOHj2qo26O-kdBu8dD_0ZItrI0SgFRcwuiX1jDpz7rbH9FayhaI',
            'role_id' => 1,
        ]);

        User::create([
            'id' => 9,
            'first_name' => 'azab',
            'last_name' => 'azab',
            'email' => 'azab@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'device_token' => 'e5vQo22ARsOXyt0Bkt-MWI:APA91bHgkPPfhg9ZHxquDEF2zcZeIOcuqHUSwotNtSHxDAU1JuIQSHlXgsESmEzyU-6MZOHj2qo26O-kdBu8dD_0ZItrI0SgFRcwuiX1jDpz7rbH9FayhaI',
            'role_id' => 1,
        ]);
        ChatRoom::create([
            'id' => 20,
            'chat_type_id' => 2,
            'sender_id' => 1,
        ]);
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' =>Hash::make('123456789'),
            'phone_number' => '01232333333',
            'role_id'=>1,
        ]);

    }
}

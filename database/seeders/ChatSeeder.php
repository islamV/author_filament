<?php

namespace Database\Seeders;

use App\Models\ChatType;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{

    public function run(): void
    {
       ChatType::create([
           "id" => 1,
           "type" => "admin",
       ]);
        ChatType::create([
            "id" => 2,
            "type" => "public",
        ]);
        ChatType::create([
            "id" => 3,
            "type" => "private",
        ]);
        ChatType::create([
            "id" => 4,
            "type" => "super_private",
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'receipt_address'],
            ['value' => 'Jl. Merdeka No. 123, Kota Bandung']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'receipt_footer'],
            ['value' => 'Thank you for your purchase!']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_phone'],
            ['value' => '0812-3456-7890']
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'receipt_address'],
            ['value' => 'Jl. Merdeka No. 123, Kota Bandung']
        );
        Setting::updateOrCreate(
            ['key' => 'receipt_footer'],
            ['value' => 'Thank you for your purchase!']
        );
        Setting::updateOrCreate(
            ['key' => 'contact_phone'],
            ['value' => '0812-3456-7890']
        );
    }
}

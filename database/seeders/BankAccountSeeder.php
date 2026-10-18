<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::updateOrCreate(
            ['title' => 'صندوق اصلی / کارتخوان'],
            [
                'bank_name' => 'بانک ملی',
                'initial_balance' => 0,
                'current_balance' => 0,
                'account_type' => 'main',
                'is_active' => true,
            ],
        );
    }
}

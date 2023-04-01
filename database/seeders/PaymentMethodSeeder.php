<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_method = [
            [
                'name' => 'cash',
                'status' => 1,
                'account_number' => '',
            ],
            [
                'name' => 'bKash',
                'status' => 1,
                'account_number' => '',
            ],
            [
                'name' => 'Nagad',
                'status' => 1,
                'account_number' => '',
            ],
            [
                'name' => 'Roket',
                'status' => 1,
                'account_number' => '',
            ],
            [
                'name' => 'Card Payment',
                'status' => 1,
                'account_number' => '',
            ],
            ];
            PaymentMethod::insert($payment_method);
    }
}

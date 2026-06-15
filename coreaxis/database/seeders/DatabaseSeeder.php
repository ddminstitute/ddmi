<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@coreaxis.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $customer1 = User::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $customer2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create accounts
        $acc1 = Account::create([
            'user_id' => $customer1->id,
            'account_number' => Account::generateAccountNumber(),
            'account_type' => 'savings',
            'balance' => 5000.00,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $acc2 = Account::create([
            'user_id' => $customer2->id,
            'account_number' => Account::generateAccountNumber(),
            'account_type' => 'checking',
            'balance' => 12500.00,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $acc3 = Account::create([
            'user_id' => $admin->id,
            'account_number' => Account::generateAccountNumber(),
            'account_type' => 'current',
            'balance' => 50000.00,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        // Seed transactions
        foreach ([[$acc1, 5000], [$acc2, 12500], [$acc3, 50000]] as [$acc, $bal]) {
            Transaction::create([
                'account_id' => $acc->id,
                'transaction_type' => 'deposit',
                'amount' => $bal,
                'balance_before' => 0,
                'balance_after' => $bal,
                'description' => 'Initial deposit on account opening',
                'reference_number' => Transaction::generateReference(),
                'status' => 'completed',
            ]);
        }
    }
}

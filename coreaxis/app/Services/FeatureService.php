<?php
namespace App\Services;

use App\Models\RoleFeaturePermission;

class FeatureService
{
    public static function allFeatures(): array
    {
        return [
            'customers'    => 'Customer Management',
            'accounts'     => 'Account Management',
            'transactions' => 'Transactions (Deposit/Withdraw/Transfer)',
            'loans'        => 'Loan Management',
            'emi'          => 'EMI Schedule',
            'collections'  => 'Collection Plans',
            'saving_plans' => 'Saving Plans',
            'employees'    => 'Employee Management',
            'attendance'   => 'Attendance Tracking',
            'payslips'     => 'Payslip Generation',
            'expenses'     => 'Company Expenses',
            'users'        => 'User Management',
            'reports'      => 'Reports & Analytics',
        ];
    }

    public static function roleHas(string $role, string $feature): bool
    {
        if ($role === 'super_admin') return true;
        return RoleFeaturePermission::isEnabled($role, $feature);
    }
}

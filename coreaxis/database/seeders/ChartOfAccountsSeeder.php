<?php
namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code'=>'1001','name'=>'Cash in Hand','type'=>'asset','normal_balance'=>'debit','is_system'=>true],
            ['code'=>'1002','name'=>'Cash at Bank','type'=>'asset','normal_balance'=>'debit','is_system'=>true],
            ['code'=>'1100','name'=>'Loans & Advances','type'=>'asset','normal_balance'=>'debit','is_system'=>true],
            ['code'=>'1200','name'=>'Fixed Deposits Placed','type'=>'asset','normal_balance'=>'debit','is_system'=>false],
            ['code'=>'1300','name'=>'Interest Receivable','type'=>'asset','normal_balance'=>'debit','is_system'=>false],
            ['code'=>'2001','name'=>'Savings Account Deposits','type'=>'liability','normal_balance'=>'credit','is_system'=>true],
            ['code'=>'2002','name'=>'Current Account Deposits','type'=>'liability','normal_balance'=>'credit','is_system'=>true],
            ['code'=>'2003','name'=>'Fixed Deposits Accepted','type'=>'liability','normal_balance'=>'credit','is_system'=>true],
            ['code'=>'2004','name'=>'Recurring Deposits','type'=>'liability','normal_balance'=>'credit','is_system'=>true],
            ['code'=>'2100','name'=>'Interest Payable','type'=>'liability','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'3001','name'=>'Share Capital','type'=>'equity','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'3002','name'=>'Retained Earnings','type'=>'equity','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'4001','name'=>'Interest Income on Loans','type'=>'income','normal_balance'=>'credit','is_system'=>true],
            ['code'=>'4002','name'=>'Processing Fee Income','type'=>'income','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'4003','name'=>'DD / Cheque Charges','type'=>'income','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'4004','name'=>'Other Income','type'=>'income','normal_balance'=>'credit','is_system'=>false],
            ['code'=>'5001','name'=>'Interest Expense on Savings','type'=>'expense','normal_balance'=>'debit','is_system'=>false],
            ['code'=>'5002','name'=>'Interest Expense on FDs','type'=>'expense','normal_balance'=>'debit','is_system'=>true],
            ['code'=>'5003','name'=>'Salary & Wages','type'=>'expense','normal_balance'=>'debit','is_system'=>false],
            ['code'=>'5004','name'=>'Operating Expenses','type'=>'expense','normal_balance'=>'debit','is_system'=>false],
        ];
        foreach ($accounts as $acc) {
            ChartOfAccount::firstOrCreate(['code'=>$acc['code']], $acc);
        }
    }
}

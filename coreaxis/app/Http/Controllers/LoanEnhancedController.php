<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanGuarantor;
use App\Models\LoanCollateral;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\ActivityLog;
use App\Models\EmiSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanEnhancedController extends Controller
{
    public function guarantors(Loan $loan)
    {
        $guarantors = $loan->guarantors ?? LoanGuarantor::where('loan_id',$loan->id)->get();
        return view('loans.guarantors', compact('loan','guarantors'));
    }

    public function addGuarantor(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'relation'        => 'required|string|max:50',
            'phone'           => 'required|string|max:20',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string|max:255',
            'id_proof_type'   => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:50',
        ]);
        $data['loan_id'] = $loan->id;
        $g = LoanGuarantor::create($data);
        ActivityLog::record('created', "Guarantor {$g->name} added to loan {$loan->loan_number}", $g);
        return redirect()->route('loans.guarantors', $loan)->with('success','Guarantor added.');
    }

    public function removeGuarantor(Loan $loan, LoanGuarantor $guarantor)
    {
        $guarantor->delete();
        return redirect()->route('loans.guarantors', $loan)->with('success','Guarantor removed.');
    }

    public function collaterals(Loan $loan)
    {
        $collaterals = LoanCollateral::where('loan_id',$loan->id)->get();
        return view('loans.collaterals', compact('loan','collaterals'));
    }

    public function addCollateral(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'collateral_type'    => 'required|in:gold,property,vehicle,fd,other',
            'description'        => 'required|string|max:255',
            'estimated_value'    => 'required|numeric|min:0',
            'valuation_date'     => 'nullable|date',
            'charge_created_date'=> 'nullable|date',
        ]);
        $data['loan_id'] = $loan->id;
        $c = LoanCollateral::create($data);
        ActivityLog::record('created', "Collateral added to loan {$loan->loan_number}: {$c->description}", $c);
        return redirect()->route('loans.collaterals', $loan)->with('success','Collateral recorded.');
    }

    public function foreclosureForm(Loan $loan)
    {
        if ($loan->status !== 'active') abort(400,'Loan is not active.');
        $foreclosureChargePercent = 2;
        $charges = round($loan->outstanding_amount * $foreclosureChargePercent / 100, 2);
        $totalPayable = round($loan->outstanding_amount + $charges, 2);
        return view('loans.foreclosure', compact('loan','charges','totalPayable','foreclosureChargePercent'));
    }

    public function foreclose(Request $request, Loan $loan)
    {
        if ($loan->status !== 'active') return back()->with('error','Loan is not active.');
        $request->validate(['confirm' => 'required|accepted']);

        $charges = round($loan->outstanding_amount * 2 / 100, 2);
        $totalPayable = $loan->outstanding_amount + $charges;
        $account = $loan->account;

        if ($account->balance < $totalPayable) {
            return back()->with('error','Insufficient balance. Need ₹'.number_format($totalPayable,2));
        }

        DB::transaction(function() use ($loan, $account, $charges, $totalPayable) {
            $before = $account->balance;
            $account->decrement('balance', $totalPayable);
            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'transaction_mode' => 'internal',
                'amount'           => $totalPayable,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => "Loan foreclosure - {$loan->loan_number} (charges: ₹".number_format($charges,2).")",
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);

            EmiSchedule::where('loan_id',$loan->id)->where('status','pending')->update(['status' => 'waived']);
            $loan->update([
                'status'               => 'closed',
                'paid_amount'          => $loan->total_amount,
                'outstanding_amount'   => 0,
                'foreclosure_date'     => today()->toDateString(),
                'foreclosure_amount'   => $loan->outstanding_amount,
                'foreclosure_charges'  => $charges,
            ]);
            ActivityLog::record('updated', "Loan {$loan->loan_number} foreclosed. Total paid: ₹".number_format($totalPayable,2), $loan);
        });

        return redirect()->route('loans.show',$loan)->with('success','Loan foreclosed successfully. No-dues certificate can be printed.');
    }

    public function restructureForm(Loan $loan)
    {
        if (!in_array($loan->status, ['active'])) abort(400);
        return view('loans.restructure', compact('loan'));
    }

    public function restructure(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'new_tenure_months' => 'required|integer|min:1|max:360',
            'new_interest_rate' => 'nullable|numeric|min:0|max:30',
            'reason'            => 'required|string|max:500',
        ]);

        DB::transaction(function() use ($loan, $data) {
            $newRate    = $data['new_interest_rate'] ?? $loan->interest_rate;
            $newEmi     = Loan::calculateEmi($loan->outstanding_amount, $newRate, $data['new_tenure_months']);
            $newTotal   = round($newEmi * $data['new_tenure_months'], 2);

            EmiSchedule::where('loan_id',$loan->id)->where('status','pending')->delete();
            $r = $newRate / 12 / 100;
            $outstanding = $loan->outstanding_amount;
            for ($i = 1; $i <= $data['new_tenure_months']; $i++) {
                $interest = round($outstanding * $r, 2);
                $principal = round($newEmi - $interest, 2);
                $outstanding = round($outstanding - $principal, 2);
                EmiSchedule::create([
                    'loan_id'              => $loan->id,
                    'installment_number'   => $i,
                    'due_date'             => now()->addMonths($i)->toDateString(),
                    'emi_amount'           => $newEmi,
                    'principal_component'  => $principal,
                    'interest_component'   => $interest,
                    'outstanding_balance'  => max(0, $outstanding),
                    'status'               => 'pending',
                ]);
            }

            $loan->update([
                'tenure_months'     => $data['new_tenure_months'],
                'interest_rate'     => $newRate,
                'emi_amount'        => $newEmi,
                'monthly_emi'       => $newEmi,
                'total_amount'      => $loan->paid_amount + $newTotal,
                'outstanding_amount'=> $loan->outstanding_amount,
                'restructured_at'   => now(),
                'restructure_reason'=> $data['reason'],
                'is_npa'            => false,
            ]);
            ActivityLog::record('updated', "Loan {$loan->loan_number} restructured. New EMI: ₹".number_format($newEmi,2)." x {$data['new_tenure_months']} months", $loan);
        });

        return redirect()->route('loans.show',$loan)->with('success','Loan restructured and EMI schedule regenerated.');
    }
}

<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StandingInstruction;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExecuteStandingInstructions extends Command
{
    protected $signature   = 'banking:execute-standing-instructions';
    protected $description = 'Process due standing instructions (auto-debit)';

    public function handle(): void
    {
        $today = today();
        $due = StandingInstruction::with('account','toAccount')
            ->where('status','active')
            ->whereDate('next_execution_date', '<=', $today)
            ->get();

        $executed = 0;
        foreach ($due as $si) {
            try {
                DB::transaction(function() use ($si, $today) {
                    $from = $si->account;
                    if ($from->balance < $si->amount) {
                        $this->warn("Insufficient balance for SI #{$si->id}");
                        return;
                    }
                    $before = $from->balance;
                    $from->decrement('balance', $si->amount);
                    Transaction::create([
                        'account_id'       => $from->id,
                        'transaction_type' => 'withdrawal',
                        'transaction_mode' => 'internal',
                        'amount'           => $si->amount,
                        'balance_before'   => $before,
                        'balance_after'    => $from->fresh()->balance,
                        'description'      => "Standing Instruction: {$si->description}",
                        'reference_number' => Transaction::generateReference(),
                        'status'           => 'completed',
                    ]);
                    if ($si->to_account_id) {
                        $to = $si->toAccount;
                        $toBefore = $to->balance;
                        $to->increment('balance', $si->amount);
                        Transaction::create([
                            'account_id'       => $to->id,
                            'transaction_type' => 'deposit',
                            'transaction_mode' => 'internal',
                            'amount'           => $si->amount,
                            'balance_before'   => $toBefore,
                            'balance_after'    => $to->fresh()->balance,
                            'description'      => "Standing Instruction credit from {$from->account_number}",
                            'reference_number' => Transaction::generateReference(),
                            'status'           => 'completed',
                        ]);
                    }

                    $next = match($si->frequency) {
                        'weekly'    => $today->copy()->addWeek(),
                        'monthly'   => $today->copy()->addMonth(),
                        'quarterly' => $today->copy()->addMonths(3),
                        'yearly'    => $today->copy()->addYear(),
                        default     => $today->copy()->addMonth(),
                    };
                    $si->update([
                        'last_executed_date'   => $today->toDateString(),
                        'next_execution_date'  => $next->toDateString(),
                        'executed_count'       => $si->executed_count + 1,
                        'status'               => ($si->end_date && $next->gt($si->end_date)) ? 'completed' : 'active',
                    ]);
                });
                $executed++;
            } catch (\Exception $e) {
                $this->error("SI #{$si->id} failed: " . $e->getMessage());
            }
        }
        $this->info("Executed {$executed} standing instructions.");
    }
}

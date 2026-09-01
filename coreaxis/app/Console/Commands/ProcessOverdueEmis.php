<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmiSchedule;
use App\Models\Loan;
use Carbon\Carbon;

class ProcessOverdueEmis extends Command
{
    protected $signature   = 'banking:process-overdue-emis';
    protected $description = 'Mark overdue EMIs and calculate penalties';

    public function handle(): void
    {
        $today    = today();
        $overdue  = EmiSchedule::with('loan')
            ->where('status','pending')
            ->whereDate('due_date', '<', $today)
            ->get();

        $count = 0;
        foreach ($overdue as $emi) {
            $days    = Carbon::parse($emi->due_date)->diffInDays($today);
            $loan    = $emi->loan;
            if (!$loan) continue;
            $penalty = round($emi->emi_amount * ($loan->penal_rate / 100) * $days / 30, 2);
            $emi->update(['status' => 'overdue', 'overdue_days' => $days, 'penalty_amount' => $penalty]);
            $loan->update(['overdue_days' => $days, 'is_npa' => $days >= 90]);
            $count++;
        }
        $this->info("Processed {$count} overdue EMIs.");
    }
}

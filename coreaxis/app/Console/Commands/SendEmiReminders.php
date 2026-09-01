<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmiSchedule;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendEmiReminders extends Command
{
    protected $signature   = 'banking:send-emi-reminders {--days=5 : Days before due date}';
    protected $description = 'Send EMI due date reminders via SMS';

    public function handle(): void
    {
        $days     = (int) $this->option('days');
        $target   = today()->addDays($days)->toDateString();
        $schedules = EmiSchedule::with('loan.account.customer','loan.account.user')
            ->where('status','pending')
            ->whereDate('due_date', $target)
            ->get();

        foreach ($schedules as $emi) {
            $loan    = $emi->loan;
            $account = $loan?->account;
            $phone   = $account?->customer?->phone ?? $account?->user?->phone;
            if ($phone) {
                NotificationService::emiReminder(
                    $account->user_id,
                    $phone,
                    $loan->loan_number,
                    $emi->emi_amount,
                    Carbon::parse($emi->due_date)->format('d M Y')
                );
            }
        }
        $this->info("Sent {$schedules->count()} EMI reminders for {$target}.");
    }
}

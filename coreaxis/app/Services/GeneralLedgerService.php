<?php
namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Transaction;
use App\Models\Loan;
use App\Models\FixedDeposit;
use Illuminate\Support\Facades\DB;

class GeneralLedgerService
{
    public function postTransaction(Transaction $txn): ?JournalEntry
    {
        try {
            return DB::transaction(function () use ($txn) {
                $lines = $this->linesForTransaction($txn);
                if (empty($lines)) return null;
                return $this->createEntry(
                    narration: $txn->description ?? $txn->transaction_type,
                    date: $txn->created_at->toDateString(),
                    source_type: 'transaction',
                    source_id: $txn->id,
                    reference: $txn->reference_number,
                    lines: $lines,
                );
            });
        } catch (\Throwable) { return null; }
    }

    public function postLoanDisbursement(Loan $loan): ?JournalEntry
    {
        try {
            return DB::transaction(function () use ($loan) {
                return $this->createEntry(
                    narration: "Loan disbursement — {$loan->loan_number}",
                    date: now()->toDateString(),
                    source_type: 'loan',
                    source_id: $loan->id,
                    reference: $loan->loan_number,
                    lines: [
                        ['code'=>'1100','type'=>'debit', 'amount'=>$loan->principal_amount],
                        ['code'=>'1001','type'=>'credit','amount'=>$loan->principal_amount],
                    ],
                );
            });
        } catch (\Throwable) { return null; }
    }

    public function postLoanPayment(Loan $loan, float $principal, float $interest, float $total): ?JournalEntry
    {
        try {
            return DB::transaction(function () use ($loan, $principal, $interest, $total) {
                $lines = [
                    ['code'=>'1001','type'=>'debit', 'amount'=>$total],
                    ['code'=>'1100','type'=>'credit','amount'=>$principal],
                ];
                if ($interest > 0) $lines[] = ['code'=>'4001','type'=>'credit','amount'=>$interest];
                return $this->createEntry(
                    narration: "Loan EMI payment — {$loan->loan_number}",
                    date: now()->toDateString(),
                    source_type: 'loan_payment',
                    source_id: $loan->id,
                    reference: $loan->loan_number,
                    lines: $lines,
                );
            });
        } catch (\Throwable) { return null; }
    }

    public function postFdOpening(FixedDeposit $fd): ?JournalEntry
    {
        try {
            return DB::transaction(function () use ($fd) {
                return $this->createEntry(
                    narration: "FD opened — {$fd->fd_number}",
                    date: $fd->start_date->toDateString(),
                    source_type: 'fixed_deposit',
                    source_id: $fd->id,
                    reference: $fd->fd_number,
                    lines: [
                        ['code'=>'2001','type'=>'debit', 'amount'=>$fd->principal_amount],
                        ['code'=>'2003','type'=>'credit','amount'=>$fd->principal_amount],
                    ],
                );
            });
        } catch (\Throwable) { return null; }
    }

    public function postFdClosure(FixedDeposit $fd, float $maturityAmount, float $interest): ?JournalEntry
    {
        try {
            return DB::transaction(function () use ($fd, $maturityAmount, $interest) {
                $lines = [
                    ['code'=>'2003','type'=>'debit', 'amount'=>$fd->principal_amount],
                    ['code'=>'2001','type'=>'credit','amount'=>$maturityAmount],
                ];
                if ($interest > 0) $lines[] = ['code'=>'5002','type'=>'debit','amount'=>$interest];
                return $this->createEntry(
                    narration: "FD closed — {$fd->fd_number}",
                    date: now()->toDateString(),
                    source_type: 'fixed_deposit',
                    source_id: $fd->id,
                    reference: $fd->fd_number,
                    lines: $lines,
                );
            });
        } catch (\Throwable) { return null; }
    }

    private function linesForTransaction(Transaction $txn): array
    {
        $depositCode = $this->depositAccountCode($txn);
        $cashCode    = '1001';
        return match($txn->transaction_type) {
            'deposit'      => [['code'=>$cashCode,'type'=>'debit','amount'=>$txn->amount],['code'=>$depositCode,'type'=>'credit','amount'=>$txn->amount]],
            'withdrawal'   => [['code'=>$depositCode,'type'=>'debit','amount'=>$txn->amount],['code'=>$cashCode,'type'=>'credit','amount'=>$txn->amount]],
            'transfer_out' => [['code'=>$depositCode,'type'=>'debit','amount'=>$txn->amount],['code'=>'2001','type'=>'credit','amount'=>$txn->amount]],
            'transfer_in'  => [],
            default        => [],
        };
    }

    private function depositAccountCode(Transaction $txn): string
    {
        $type = optional($txn->account)->account_type ?? 'savings';
        return $type === 'current' ? '2002' : '2001';
    }

    private function createEntry(string $narration, string $date, string $source_type, int $source_id, string $reference, array $lines): JournalEntry
    {
        $totalDebit  = collect($lines)->where('type','debit')->sum('amount');
        $totalCredit = collect($lines)->where('type','credit')->sum('amount');

        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateEntryNumber(),
            'entry_date'   => $date,
            'narration'    => $narration,
            'source_type'  => $source_type,
            'source_id'    => $source_id,
            'reference'    => $reference,
            'total_debit'  => $totalDebit,
            'total_credit' => $totalCredit,
            'is_balanced'  => abs($totalDebit - $totalCredit) < 0.01,
            'created_by'   => auth()->id(),
        ]);

        foreach ($lines as $line) {
            $account = ChartOfAccount::byCode($line['code']);
            JournalEntryLine::create([
                'journal_entry_id'    => $entry->id,
                'chart_of_account_id' => $account->id,
                'type'                => $line['type'],
                'amount'              => $line['amount'],
                'narration'           => $line['narration'] ?? null,
            ]);
        }
        return $entry;
    }
}

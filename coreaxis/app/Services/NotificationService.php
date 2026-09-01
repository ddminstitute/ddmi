<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public static function sendSms(string $phone, string $message): bool
    {
        $gateway = config('services.sms.gateway', 'log');

        if ($gateway === 'log') {
            Log::info("SMS to {$phone}: {$message}");
            return true;
        }

        try {
            if ($gateway === 'msg91') {
                $apiKey   = config('services.sms.msg91_key');
                $senderId = config('services.sms.msg91_sender', 'CORXFS');
                $url = "https://api.msg91.com/api/sendhttp.php?authkey={$apiKey}&mobiles={$phone}&message=" . urlencode($message) . "&route=4&sender={$senderId}&country=91";
                file_get_contents($url);
            } elseif ($gateway === 'fast2sms') {
                $apiKey = config('services.sms.fast2sms_key');
                $ch = curl_init("https://www.fast2sms.com/dev/bulkV2");
                curl_setopt_array($ch, [
                    CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => http_build_query(['variables_values' => $message,'route' => 'q','numbers' => $phone]),
                    CURLOPT_HTTPHEADER => ["authorization: {$apiKey}"],
                ]);
                curl_exec($ch); curl_close($ch);
            }
        } catch (\Exception $e) {
            Log::error("SMS failed to {$phone}: " . $e->getMessage());
            return false;
        }
        return true;
    }

    public static function transactionAlert(Transaction $txn): void
    {
        $account  = $txn->account;
        $customer = $account?->customer;
        $phone    = $customer?->phone ?? $account?->user?->phone;
        if (!$phone) return;

        $type   = in_array($txn->transaction_type, ['deposit','transfer_in']) ? 'Credited' : 'Debited';
        $amount = number_format($txn->amount, 2);
        $bal    = number_format($txn->balance_after, 2);
        $ref    = $txn->reference_number;

        $msg = "CoreAxis: ₹{$amount} {$type} to A/c " . substr($account->account_number, -4)
             . ". Bal: ₹{$bal}. Ref: {$ref}. -CoreAxis Financial";

        self::sendSms($phone, $msg);
    }

    public static function emiReminder(int $userId, string $phone, string $loanNo, float $emi, string $dueDate): void
    {
        $msg = "CoreAxis EMI Reminder: Your EMI of ₹" . number_format($emi, 2)
             . " for loan {$loanNo} is due on {$dueDate}. Please ensure sufficient balance. -CoreAxis Financial";
        self::sendSms($phone, $msg);
    }
}

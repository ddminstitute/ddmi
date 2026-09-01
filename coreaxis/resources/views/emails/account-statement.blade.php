<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body{font-family:Arial,sans-serif;font-size:13px;color:#333;margin:0;padding:20px;background:#f5f5f5}
.container{max-width:680px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)}
.header{background:linear-gradient(135deg,#1565C0,#1976D2);color:#fff;padding:24px 28px}
.header h2{margin:0;font-size:1.3rem}
.header p{margin:4px 0 0;opacity:.8;font-size:.85rem}
.body{padding:24px 28px}
.stat-row{display:flex;justify-content:space-between;background:#f0f4ff;border-radius:6px;padding:12px 16px;margin-bottom:16px}
.stat-box{text-align:center}
.stat-box .label{font-size:.7rem;color:#666;text-transform:uppercase;letter-spacing:.5px}
.stat-box .value{font-size:1.1rem;font-weight:700;color:#1565C0}
table{width:100%;border-collapse:collapse;margin-top:12px}
th{background:#f1f5f9;font-size:.7rem;text-transform:uppercase;color:#888;padding:8px;text-align:left;border-bottom:2px solid #e5e7eb}
td{padding:8px;border-bottom:1px solid #f1f1f1;font-size:.8rem}
.credit{color:#16a34a;font-weight:600}
.debit{color:#dc2626;font-weight:600}
.footer{background:#f8f8f8;padding:16px 28px;font-size:.75rem;color:#888;text-align:center;border-top:1px solid #eee}
</style>
</head>
<body>
<div class="container">
<div class="header">
    <h2>&#127974; CoreAxis Financial</h2>
    <p>Account Statement &mdash; {{ $from }} to {{ $to }}</p>
</div>
<div class="body">
    <p>Dear {{ $account->user?->name ?? 'Valued Customer' }},</p>
    <p>Please find your account statement for the period <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong> below.</p>

    <div class="stat-row">
        <div class="stat-box">
            <div class="label">Account Number</div>
            <div class="value">{{ $account->account_number }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Current Balance</div>
            <div class="value">₹{{ number_format($account->balance,2) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Transactions</div>
            <div class="value">{{ $transactions->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Description</th>
                <th>Type</th>
                <th style="text-align:right">Amount</th>
                <th style="text-align:right">Balance</th>
            </tr>
        </thead>
        <tbody>
        @forelse($transactions as $txn)
        <tr>
            <td>{{ $txn->created_at->format('d M Y') }}</td>
            <td style="font-family:monospace;font-size:.75rem">{{ $txn->reference_number }}</td>
            <td>{{ $txn->description }}</td>
            <td>{{ $txn->getTypeLabel() }}</td>
            <td style="text-align:right" class="{{ in_array($txn->transaction_type,['deposit','transfer_in']) ? 'credit' : 'debit' }}">
                {{ in_array($txn->transaction_type,['deposit','transfer_in']) ? '+' : '-' }}₹{{ number_format($txn->amount,2) }}
            </td>
            <td style="text-align:right">₹{{ number_format($txn->balance_after,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">No transactions in this period</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="footer">
    This is a system-generated statement from CoreAxis Financial. Do not reply to this email.<br>
    Generated on {{ now()->format('d M Y H:i') }}
</div>
</div>
</body>
</html>

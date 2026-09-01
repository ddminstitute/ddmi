<?php

namespace App\Mail;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountStatement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Account $account,
        public $transactions,
        public string $from,
        public string $to
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Account Statement — {$this->account->account_number} ({$this->from} to {$this->to})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-statement',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

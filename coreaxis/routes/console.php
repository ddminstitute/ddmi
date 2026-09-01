<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Banking scheduled tasks
Schedule::command('banking:process-overdue-emis')->dailyAt('23:00');
Schedule::command('banking:send-emi-reminders --days=5')->dailyAt('09:00');
Schedule::command('banking:send-emi-reminders --days=1')->dailyAt('09:00');
Schedule::command('banking:execute-standing-instructions')->dailyAt('08:00');

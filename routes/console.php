<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservations:expire', function () {
    $count = 0;
    \App\Models\Reservation::where('status', 'active')
        ->where('end_time', '<', now())
        ->each(function ($res) use (&$count) {
            $res->update(['status' => 'completed']);
            if ($res->spot) {
                $res->spot->update(['status' => 'available']);
            }
            $count++;
        });
    $this->info("Expired {$count} reservations.");
})->purpose('Auto-expire reservations past their end time');

Artisan::command('notifications:cleanup', function () {
    $deleted = \App\Models\Notification::where('created_at', '<', now()->subDays(90))->delete();
    $this->info("Deleted {$deleted} old notifications.");
})->purpose('Remove notifications older than 90 days');

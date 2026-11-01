<?php
/**
 * Mahalakshmi Netralaya - remind patients about an upcoming appointment.
 *
 * Replaces attendanceNotification from ICTSchool, which chased absent students and
 * texted their parents. A hospital does not take attendance, it books appointments,
 * so the same reminder plumbing now sends the patient a reminder for the day.
 *
 * Usage:
 *   php artisan hospital:appointment-reminder            reminds about tomorrow
 *   php artisan hospital:appointment-reminder --days=0   reminds about today
 *
 * Copyright (c) Mahalakshmi Netralaya
 * Part of Mahalakshmi Netralaya <https://mahalakshminetralaya.com>
 * Licensed under the GNU General Public License v3.0.
 */

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentReminder extends Command
{
    protected $signature = 'hospital:appointment-reminder {--days=1 : how many days ahead to remind about}';

    protected $description = 'Send an SMS reminder to every patient with an appointment on the target day';

    /** Contacts pushed in a single run. */
    protected $batchLimit = 200;

    /** Local numbers starting with a single 0 get this country code instead. */
    protected $countryCode = '91';

    public function handle()
    {
        $target = Carbon::now()->addDays((int) $this->option('days'))->format('Y-m-d');

        foreach (['appointment', 'patient', 'notification_type'] as $table) {
            if (! Schema::hasTable($table)) {
                $this->warn('Skipped: table ' . $table . ' is missing.');

                return self::SUCCESS;
            }
        }

        $notification = DB::table('notification_type')
            ->where('notification', 'appointment')
            ->first();

        if (! $notification || empty($notification->type)) {
            $this->warn('Skipped: no notification type is configured for appointments.');

            return self::SUCCESS;
        }

        $appointments = $this->appointmentsOn($target);

        if ($appointments->isEmpty()) {
            $this->info('Nothing to send. No appointments on ' . $target . '.');

            return self::SUCCESS;
        }

        $this->sendReminders($appointments, $target, $notification);

        $this->info('Reminder queued for ' . count($appointments) . ' patient(s) with appointments on ' . $target . '.');

        return self::SUCCESS;
    }

    /**
     * Appointments on one day, with the patient's contact details.
     *
     * `appointment.date` is varchar in the legacy schema, so it is compared as a
     * date rather than as a string.
     */
    protected function appointmentsOn($target)
    {
        $query = DB::table('appointment')
            ->select([
                DB::raw('patient.name as name'),
                DB::raw('patient.phone as phone'),
                DB::raw('patient.email as email'),
            ])
            ->join('patient', 'patient.id', '=', 'appointment.patient')
            ->whereRaw('DATE(appointment.date) = ?', [$target])
            ->limit($this->batchLimit);

        if (Schema::hasColumn('appointment', 'status')) {
            $query->where(function ($q) {
                $q->whereNull('appointment.status')
                  ->orWhereNotIn('appointment.status', ['cancelled', 'Cancelled', 'completed', 'Completed']);
            });
        }

        return $query->get();
    }

    protected function sendReminders($appointments, $target, $notification)
    {
        $numbers = [];

        foreach ($appointments as $row) {
            $phone = $this->normalisePhone($row->phone);

            if ($phone === null) {
                continue;
            }

            $numbers[] = $phone;
        }

        if (empty($numbers)) {
            $this->warn('Skipped: no usable phone numbers on those appointments.');

            return;
        }

        // TODO: Integrate with your SMS provider here (Twilio, AWS SNS, etc.)
        // For now, we just log the reminders
        foreach ($numbers as $phone) {
            $this->sendSms($phone, $this->message($target));
        }

        $this->info('Reminder sent to ' . count($numbers) . ' patient(s) with appointments on ' . $target . '.');
    }

    protected function sendSms($phone, $message)
    {
        // TODO: Implement actual SMS sending with your provider
        // Examples: Twilio, AWS SNS, Vonage, Plivo, etc.
        $this->line("SMS to {$phone}: {$message}");
    }

    protected function message($target)
    {
        return 'Reminder: you have an appointment at Mahalakshmi Netralaya on ' . $target . '. Please arrive ten minutes early.';
    }

    protected function normalisePhone($phone)
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return null;
        }

        if (preg_match('~^0\d+$~', $phone)) {
            $phone = $this->countryCode . substr($phone, 1);
        }

        $digits = preg_replace('/\D/', '', $phone);

        return strlen($digits) >= 10 ? $digits : null;
    }
}

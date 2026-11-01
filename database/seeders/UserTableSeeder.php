<?php
/**
 * Mahalakshmi Netralaya - the minimum a fresh install needs to be usable.
 *
 * The version this replaces came from ICTSchool. It loaded seven SQL dumps of
 * demo school data, students, classes, sections, subjects, marks, grades and
 * teachers, and six of those files were never committed, so `migrate --seed`
 * failed on every clean install. It also seeded a previous customer's name and
 * website as the institute.
 *
 * This seeds an administrator, a blank hospital record, the permission
 * catalogue with everything granted to Admin, and the two notification types the
 * reminder jobs look for. No demo patients: a hospital system should not ship
 * with invented people in it.
 *
 * Copyright (c) Mahalakshmi Netralaya
 * Part of Mahalakshmi Netralaya EMR
 * Licensed under the GNU General Public License v3.0.
 */

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->users();
        $this->hospital();
        $this->permissions();
        $this->notificationTypes();
        $this->demoUsers();
    }

    /**
     * One administrator. Change the password before the server faces anything.
     */
    protected function users(): void
    {
        if (DB::table('users')->count() > 0) {
            $this->command->info('Users already present, left alone.');

            return;
        }

        User::create([
            'firstname' => 'Hospital',
            'lastname' => 'Administrator',
            'login' => 'admin',
            'email' => 'admin@mahalakshminetralaya.com',
            'group' => 'Admin',
            'desc' => 'Default administrator created by the installer',
            'password' => Hash::make('123456'),
        ]);

        $this->command->warn('Created the admin account with the default password. Change it now.');
    }

    /**
     * Demo users for each role - for testing and demonstration.
     * In production, remove or modify these.
     */
    protected function demoUsers(): void
    {
        $demoPassword = 'Demo@2026';

        $demoUsers = [
            // Doctor
            ['doctor', 'Dr. Rahul', 'Sharma', 'Doctor', 'rahul.sharma@mahalakshminetralaya.com'],
            // Nurse
            ['nurse', 'Priya', 'Patel', 'Nurse', 'priya.patel@mahalakshminetralaya.com'],
            // Receptionist
            ['reception', 'Front', 'Desk', 'Receptionist', 'frontdesk@mahalakshminetralaya.com'],
            // Pharmacist
            ['pharmacist', 'Amit', 'Kumar', 'Pharmacist', 'amit.kumar@mahalakshminetralaya.com'],
            // Pharmacy Staff (for daily sales entry)
            ['pharmacy', 'Medical', 'Store', 'Pharmacy Staff', 'pharmacy@mahalakshminetralaya.com'],
            // Optometrist
            ['optometrist', 'Dr. Priya', 'Nair', 'Optometrist', 'priya.nair@mahalakshminetralaya.com'],
            // Optician
            ['optician', 'Rajesh', 'Verma', 'Optician', 'rajesh.verma@mahalakshminetralaya.com'],
            // Orthoptist
            ['orthoptist', 'Dr. Meera', 'Iyer', 'Orthoptist', 'meera.iyer@mahalakshminetralaya.com'],
            // Camp Coordinator
            ['campcoord', 'Sanjay', 'Gupta', 'Camp Coordinator', 'sanjay.gupta@mahalakshminetralaya.com'],
            // Accountant
            ['accountant', 'Finance', 'Team', 'Accountant', 'finance@mahalakshminetralaya.com'],
        ];

        foreach ($demoUsers as [$login, $first, $last, $group, $email]) {
            User::firstOrCreate(['login' => $login], [
                'firstname' => $first,
                'lastname' => $last,
                'email' => $email,
                'group' => $group,
                'desc' => 'Demo user (' . strtolower($group) . ')',
                'password' => Hash::make($demoPassword),
            ]);
        }

        $this->command->info('Demo users created with password: ' . $demoPassword);
    }

    /**
     * A blank hospital record so Settings has something to edit. The name is
     * used as the SMS sender mask, so it is worth setting properly.
     */
    protected function hospital(): void
    {
        if (Institute::count() > 0) {
            return;
        }

        Institute::create([
            'name' => 'Mahalakshmi Netralaya',
            'establish' => date('Y'),
            'email' => 'info@mahalakshminetralaya.com',
            'web' => 'https://mahalakshminetralaya.com',
            'phoneNo' => '+91-XXXX-XXXXXX',
            'address' => 'Your Hospital Address Here',
        ]);

        $this->command->info('Hospital record created. Set your details in Settings.');
    }

    /**
     * The permission catalogue, with every right granted to Admin and none to
     * the other roles. Nothing is granted quietly.
     */
    protected function permissions(): void
    {
        if (! Schema::hasTable('permission') || DB::table('permission')->count() > 0) {
            return;
        }

        $groups = array_keys(config('hospital_permissions.groups', []));
        $fields = config('hospital_permissions.permissions', []);

        if (empty($groups) || empty($fields)) {
            $this->command->warn('No permission catalogue in config, skipped.');

            return;
        }

        $rows = [];

        foreach ($groups as $group) {
            foreach ($fields as $field) {
                $rows[] = [
                    'permission_name' => str_replace(' ', '_', strtolower($field)),
                    'permission_group' => $group,
                    'permission_type' => $group === 'admin' ? 'yes' : 'no',
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('permission')->insert($chunk);
        }

        $this->command->info(sprintf(
            '%d permissions seeded across %d roles, all granted to Admin.',
            count($fields), count($groups)
        ));
    }

    /**
     * The reminder jobs look these up before they do anything. Seeding them as
     * SMS means the jobs report a missing ICTCore integration rather than a
     * missing notification type, which is the more useful message.
     */
    protected function notificationTypes(): void
    {
        if (! Schema::hasTable('notification_type')) {
            return;
        }

        foreach (['appointment', 'payment'] as $name) {
            if (DB::table('notification_type')->where('notification', $name)->exists()) {
                continue;
            }

            DB::table('notification_type')->insert([
                'notification' => $name,
                'type' => 'sms',
            ]);
        }
    }
}

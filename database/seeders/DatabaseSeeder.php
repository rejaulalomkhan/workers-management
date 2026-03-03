<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Worker;
use App\Models\WorkerRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@fhts.com',
            'password' => Hash::make('password'),
        ]);

        // Settings
        Setting::create([
            'company_name' => 'FAIZA HOST TECHNICAL SERVICES L.L.C',
            'company_name_arabic' => 'شركة فايزة هوست للخدمات الفنية ذ.م.م',
            'trn' => '123456789012345',
            'address' => 'P.O. Box: 12345, Dubai - U.A.E.',
            'phone' => '+971 4 123 4567',
            'email' => 'info@fhts.com',
            'currency' => 'AED',
            'vat_rate' => 5.00,
        ]);

        // Projects
        $project1 = Project::create([
            'name' => 'Al Qusais Residential Building',
            'location' => 'Al Qusais, Dubai',
            'customer_name' => 'ABC Construction',
            'customer_address' => 'Business Bay, Dubai',
            'customer_trn' => '987654321098765',
        ]);

        $project2 = Project::create([
            'name' => 'Downtown Commercial Tower',
            'location' => 'Downtown, Dubai',
            'customer_name' => 'XYZ Real Estate',
            'customer_address' => 'Sheikh Zayed Road, Dubai',
            'customer_trn' => '555555555555555',
        ]);

        // Categories
        ProjectCategory::create(['project_id' => $project1->id, 'name' => 'Mason', 'billing_rate' => 20]);
        ProjectCategory::create(['project_id' => $project1->id, 'name' => 'Helper', 'billing_rate' => 15]);
        ProjectCategory::create(['project_id' => $project1->id, 'name' => 'Foreman', 'billing_rate' => 30]);

        ProjectCategory::create(['project_id' => $project2->id, 'name' => 'Mason', 'billing_rate' => 22]);
        ProjectCategory::create(['project_id' => $project2->id, 'name' => 'Helper', 'billing_rate' => 16]);

        // Workers
        $workers = [
            ['name' => 'John Doe', 'trade' => 'Mason', 'pay' => 12],
            ['name' => 'Ali Khan', 'trade' => 'Helper', 'pay' => 10],
            ['name' => 'Omar Silva', 'trade' => 'Foreman', 'pay' => 18],
            ['name' => 'Rajesh Kumar', 'trade' => 'Mason', 'pay' => 12],
            ['name' => 'David Smith', 'trade' => 'Helper', 'pay' => 10],
        ];

        foreach ($workers as $w) {
            $worker = Worker::create([
                'name' => $w['name'],
                'worker_id_number' => 'W-' . rand(1000, 9999),
                'trade' => $w['trade'],
                'internal_pay_rate' => $w['pay']
            ]);
            WorkerRate::create([
                'worker_id' => $worker->id,
                'rate' => $w['pay'],
                'effective_from' => Carbon::now()->subMonths(6)
            ]);
        }
    }
}

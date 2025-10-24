<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobMaster;
use App\Models\Customer;
use App\Models\Product;
use App\Models\JobType;
use App\Models\JobItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Jobs Master
        $jobs = [
            ['name' => 'Manager', 'description' => 'Manager position'],
            ['name' => 'Project Manager', 'description' => 'Project Manager position'],
            ['name' => 'EP/SIEM', 'description' => 'EP/SIEM position'],
            ['name' => 'AppSec', 'description' => 'Application Security position'],
            ['name' => 'Network', 'description' => 'Network Engineer position'],
            ['name' => 'SAT', 'description' => 'SAT position'],
            ['name' => 'Vuln/Acc', 'description' => 'Vulnerability/Access position'],
        ];

        foreach ($jobs as $job) {
            JobMaster::create($job);
        }

        // Seed Users
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'name' => 'Administrator',
            'job_id' => 1,
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'user',
            'password' => Hash::make('password'),
            'name' => 'Regular User',
            'job_id' => 3,
            'role' => 'user',
            'status' => 'active',
        ]);

        // Seed Customers
        $customers = [
            ['name' => 'Customer A', 'description' => 'First customer'],
            ['name' => 'Customer B', 'description' => 'Second customer'],
            ['name' => 'Customer C', 'description' => 'Third customer'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Seed Products
        $products = [
            ['name' => 'Product 1', 'description' => 'First product'],
            ['name' => 'Product 2', 'description' => 'Second product'],
            ['name' => 'Product 3', 'description' => 'Third product'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Seed Job Types
        $jobTypes = [
            ['name' => 'Implementation', 'description' => 'Implementation tasks'],
            ['name' => 'Maintenance', 'description' => 'Maintenance tasks'],
            ['name' => 'Support', 'description' => 'Support tasks'],
            ['name' => 'Analysis', 'description' => 'Analysis tasks'],
            ['name' => 'Research', 'description' => 'Research tasks'],
        ];

        foreach ($jobTypes as $jobType) {
            JobType::create($jobType);
        }

        // Seed Job Items
        $jobItems = [
            ['name' => 'Configuration', 'description' => 'System configuration'],
            ['name' => 'Testing', 'description' => 'System testing'],
            ['name' => 'Documentation', 'description' => 'Documentation work'],
            ['name' => 'Troubleshooting', 'description' => 'Problem solving'],
            ['name' => 'Deployment', 'description' => 'System deployment'],
        ];

        foreach ($jobItems as $jobItem) {
            JobItem::create($jobItem);
        }
    }
}

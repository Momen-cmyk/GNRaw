<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPARING USERS AND CUSTOMERS TABLES ===\n\n";

echo "USERS TABLE:\n";
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} - Name: {$user->name} - Email: {$user->email} - Username: {$user->username} - Status: {$user->status->value}\n";
}

echo "\nCUSTOMERS TABLE:\n";
$customers = App\Models\Customer::all();
foreach ($customers as $customer) {
    echo "ID: {$customer->id} - Name: {$customer->name} - Email: {$customer->email} - Username: {$customer->username} - Active: " . ($customer->is_active ? 'Yes' : 'No') . "\n";
}

echo "\n=== RECOMMENDATION ===\n";
echo "The 'customers' table should be merged into the 'users' table.\n";
echo "The 'users' table should be the main table for regular users/customers.\n";
echo "Admins and Suppliers should have their own separate tables.\n";

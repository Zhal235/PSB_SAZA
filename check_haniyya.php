<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "Looking for user Haniyya...\n";

// Search for Haniyya
$user = \App\Models\User::where('name', 'LIKE', '%Haniyya%')
    ->orWhere('name', 'LIKE', '%haniyya%')
    ->first();

if ($user) {
    echo "Found: ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
} else {
    echo "User Haniyya not found\n";
    echo "All users:\n";
    $users = \App\Models\User::all();
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    }
}
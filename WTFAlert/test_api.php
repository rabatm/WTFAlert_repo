<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test user data
$user = App\Models\User::first();
echo "User email: " . $user->email . "\n";

$habitant = $user->habitant;
if ($habitant) {
    echo "Habitant ID: " . $habitant->id . "\n";
    $foyers = $habitant->foyers()->get();
    echo "Foyers count: " . $foyers->count() . "\n";
    foreach ($foyers as $foyer) {
        echo "- Foyer: " . $foyer->nom . "\n";
    }
} else {
    echo "No habitant for this user\n";
}

<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;

$cars = Car::all();

// Clear existing
foreach($cars as $c) {
    $c->maintenances = [];
    $c->save();
}

$c1 = Car::find(1);
if($c1) {
    $c1->maintenances = [
        ['maintenance_date' => '2026-01-10', 'end_date' => '2026-01-11', 'cost' => 2500000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Ganti oli mesin, filter udara, filter oli', 'status' => 'completed', 'branch_id' => 1],
        ['maintenance_date' => '2026-03-15', 'end_date' => '2026-03-16', 'cost' => 2800000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Servis rem, tune up', 'status' => 'completed', 'branch_id' => 1],
        ['maintenance_date' => '2026-05-10', 'end_date' => '2026-05-11', 'cost' => 3200000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Ganti oli transmisi, balancing', 'status' => 'completed', 'branch_id' => 1],
    ];
    $c1->save();
}

$c2 = Car::find(2);
if($c2) {
    $c2->maintenances = [
        ['maintenance_date' => '2026-02-12', 'end_date' => '2026-02-13', 'cost' => 3500000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Servis rutin berkala berkendara', 'status' => 'completed', 'branch_id' => 1],
        ['maintenance_date' => '2026-04-20', 'end_date' => '2026-04-23', 'cost' => 12000000, 'maintenance_type' => 'Perbaikan Mesin', 'description' => 'Overhaul sistem pendingin radiator', 'status' => 'completed', 'branch_id' => 1]
    ];
    $c2->save();
}

$c5 = Car::find(5);
if($c5) {
    $c5->maintenances = [
        ['maintenance_date' => '2026-03-01', 'end_date' => '2026-03-02', 'cost' => 1800000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Ganti filter solar, carbon clean', 'status' => 'completed', 'branch_id' => 1]
    ];
    $c5->save();
}

$c8 = Car::find(8);
if($c8) {
    $c8->maintenances = [
        ['maintenance_date' => '2026-03-22', 'end_date' => '2026-03-23', 'cost' => 1500000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Tune up berkala', 'status' => 'completed', 'branch_id' => 1],
        ['maintenance_date' => '2026-05-25', 'end_date' => '2026-05-27', 'cost' => 4500000, 'maintenance_type' => 'Body Repair', 'description' => 'Pengecatan pintu kanan penyok ringan', 'status' => 'completed', 'branch_id' => 1]
    ];
    $c8->save();
}

$c9 = Car::find(9);
if($c9) {
    $c9->maintenances = [
        ['maintenance_date' => '2026-04-05', 'end_date' => '2026-04-06', 'cost' => 1200000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Ganti oli berkala', 'status' => 'completed', 'branch_id' => 1],
        ['maintenance_date' => '2026-05-29', 'end_date' => null, 'cost' => 1200000, 'maintenance_type' => 'Ganti Ban', 'description' => 'Ganti ban depan kanan kiri bocor halus', 'status' => 'in_progress', 'branch_id' => 1]
    ];
    $c9->status = 'maintenance';
    $c9->save();
}

$c13 = Car::find(13);
if($c13) {
    $c13->maintenances = [
        ['maintenance_date' => '2026-05-30', 'end_date' => null, 'cost' => 800000, 'maintenance_type' => 'Servis Rutin', 'description' => 'Pemeriksaan berkala rutin bulanan', 'status' => 'scheduled', 'branch_id' => 1]
    ];
    $c13->save();
}

echo "Maintenance seeded successfully!\n";

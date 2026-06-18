<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$zones = [
    "ZA" => ["lat" => 27.5640380, "lng" => 41.6834410],
    "ZB" => ["lat" => 27.5642000, "lng" => 41.6830000],
    "ZC" => ["lat" => 27.5638000, "lng" => 41.6838000],
    "ZD" => ["lat" => 27.5645000, "lng" => 41.6835000]
];
foreach(\App\Models\ParkingZone::all() as $zone) {
    if(isset($zones[$zone->code])) {
        $zone->latitude = $zones[$zone->code]["lat"];
        $zone->longitude = $zones[$zone->code]["lng"];
        $zone->save();

        $spots = $zone->spots;
        $count = count($spots);
        $gridSize = ceil(sqrt($count));
        foreach($spots as $i => $spot) {
            $row = floor($i / $gridSize);
            $col = $i % $gridSize;
            $spot->latitude = $zone->latitude + ($row * 0.0001) - 0.0002;
            $spot->longitude = $zone->longitude + ($col * 0.0001) - 0.0002;
            $spot->save();
        }
    }
}
echo "Done";


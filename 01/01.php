<?php

function fuelRequirement($mass) {
    $fuel = floor(intval($mass) / 3) - 2;
    if ($fuel > 0) {
        $fuel += fuelRequirement($fuel);
        return $fuel;
    } else {
        return 0;
    }
}

$file = fopen('input.txt', 'r');
$sum = 0;
while ($mass = fgets($file)) {
    $sum += fuelRequirement($mass);
}
echo $sum . "\n";
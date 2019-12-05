<?php

function get_coord($direction, $last_coord) {
    switch($direction[0]) {
        case 'R':
            $last_coord[0] += intval(substr($direction, 1));
            break;
        case 'L':
            $last_coord[0] -= intval(substr($direction, 1));
            break;
        case 'U':
            $last_coord[1] += intval(substr($direction, 1));
            break;
        case 'D':
            $last_coord[1] -= intval(substr($direction, 1));
            break;
        default:
            echo "Bad direction: $direction";
            exit;
    }
    return $last_coord;
}

function build_lines($wire) {
    $directions = explode(',', $wire);
    
    $last_coord = [0,0];
    foreach($directions as $direction) {
        $new_coord = get_coord($direction, $last_coord);
        $lines[] = [$last_coord, $new_coord];
        $last_coord = $new_coord;
    }
    return $lines;
}

function is_vertical($coords) {
    return ($coords[0][0] == $coords[1][0]);
}

function is_horizontal($coords) {
    return ($coords[0][1] == $coords[1][1]);
}

function are_perpendicular($line1, $line2) {
    if ((is_vertical($line1) && is_horizontal($line2))
        || (is_horizontal($line1) && is_vertical($line2))) {
        return true;
    }
    return false;
}

function is_between($a, $b, $c) {
    return (($a >= $b && $a <= $c)
        || ($a >= $c && $a <= $b));
}

function x_within_bounds($line1, $line2) {
    return (is_between($line1[0][0], $line2[0][0], $line2[1][0]));
}

function y_within_bounds($line1, $line2) {
    return (is_between($line2[0][1], $line1[0][1], $line1[1][1]));
}

function within_bounds($line1, $line2) {
    return ((x_within_bounds($line1, $line2) && y_within_bounds($line1, $line2))
            || (x_within_bounds($line2, $line1) && y_within_bounds($line2, $line1)));
}

function calc_intersection($line1, $line2) {
    if (is_horizontal($line1)) {
        return [$line2[0][0], $line1[0][1]];
    } else {
        return [$line1[0][0], $line2[0][1]];
    }
}

function do_lines_cross($line1, $line2) {
    if (are_perpendicular($line1, $line2)
        && within_bounds($line1, $line2)) {
        return calc_intersection($line1, $line2);
    }
    return false;
}

function find_intersections($wires) {
    $intersections = [];
    foreach ($wires[0] as $wire1) {
        foreach ($wires[1] as $wire2) {
            $intersection = do_lines_cross($wire1, $wire2);
            if ($intersection) {
                $intersections[] = $intersection;
            }
        }
    }
    return $intersections;
}

function calc_distances($intersections) {
    $distances = [];
    foreach ($intersections as $intersection) {
        $distances[] = abs($intersection[0]) + abs($intersection[1]);
    }
    return array_filter($distances, function($dist) {
        return ($dist > 0);
    });
}

$file = fopen('input.txt', 'r');
while ($wire = fgets($file)) {
    $wires[] = build_lines($wire);
}
$intersections = find_intersections($wires);
$distances = calc_distances($intersections);
echo min($distances) . "\n";
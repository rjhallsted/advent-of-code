<?php

function op_add(&$ops, $index) {
    $sum = $ops[$ops[$index + 1]] + $ops[$ops[$index + 2]];
    $ops[$ops[$index + 3]] = $sum;
}

function op_multiply(&$ops, $index) {
    $sum = $ops[$ops[$index + 1]] * $ops[$ops[$index + 2]];
    $ops[$ops[$index + 3]] = $sum;
}

function do_op(&$ops, $index) {
    if ($ops[$index] == 1) {
        op_add($ops, $index);
    } else if ($ops[$index == 2]) {
        op_multiply($ops, $index);
    } else {
        echo "Encountered unknown opcode $ops[$index] at postion $index";
        exit;
    }
}

function run_computer($ops, $noun, $verb) {
    $ops[1] = $noun;
    $ops[2] = $verb;

    $i = 0;
    while($i < count($ops)) {
        if ($ops[$i] == 99) {
            break;
        }
        do_op($ops, $i);
        $i += 4;   
    }
    return $ops;
}

function find_result($ops, $goal) {
    for($i = 0; $i < 100; $i++) {
        for ($j = 0; $j< 100; $j++) {
            $result = run_computer($ops, $i, $j);
            if ($result[0] == $goal) {
                return $result;
            }
        }
    }
    echo "Failed to find the answer.\n";
    exit;
}


$file = fopen('input.txt', 'r');
$intcode = fgets($file);
$ops = explode(',', $intcode);

$result = find_result($ops, 19690720);
echo "$result[1], $result[2]\n";
echo (100 * $result[1] + $result[2]) . "\n";

<?php

function eval_expr($entry)
{

    $numbers = [];
    $operators = [];
    $priority = [
        "+" => 0,
        "-" => 0,
        "*" => 1,
        "/" => 1,
        "%" => 1,
    ];

    $operations = preg_split("~(\d+|[()*/+-])~", $entry, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

    foreach ($operations as $key => $value) {
        if (is_numeric($value) == true) {
            array_push($numbers, $value);
        } elseif (array_key_exists($value, $priority) || $operators == 0 || $priority[end($operators)] > $priority[$value]) {
            array_push($operators, $value);
        } elseif ($value == ")") {
            while (end($operators) !== "(") {
                $val = array_pop($operators);
                array_push($numbers, $val);
            }
            array_pop($operators);
        } else {
            array_push($operators, $value);
        }
    }

    while (empty($operators) == false) {
        $value = array_pop($operators);
        array_push($numbers, $value);
    }

    $operations = [
        "+" => function ($a, $b) {
            return $a + $b;
        },
        "-" => function ($a, $b) {
            return $a - $b;
        },
        "/" => function ($a, $b) {
            return $a / $b;
        },
        "*" => function ($a, $b) {
            return $a * $b;
        },
        "%" => function ($a, $b) {
            return $a % $b;
        },
    ];

    while (count($numbers) > 1) {
        $operator_index = 0;
        while (!isset($operations[$numbers[$operator_index]]))
            $operator_index++;
        $operator = $numbers[$operator_index];
        $a = $numbers[$operator_index - 2];
        $b = $numbers[$operator_index - 1];
        $result = $operations[$operator]($a, $b);
        array_splice($numbers, $operator_index - 2, 3, [$result]);
    }
    return $numbers[0];
}
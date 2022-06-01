<?php

function distance(string $strandA, string $strandB) : int
{
    if (strlen($strandA) !== strlen($strandB)) {
        throw new \InvalidArgumentException('DNA strands must be of equal length.');
    }

    if ($strandA === $strandB) {
        return 0;
    }

    $distance = 0;

    for ($i = 0, $strLength = strlen($strandA); $i < $strLength; $i++) {
        if ($strandA[$i] !== $strandB[$i]) {
            $distance++;
        }
    }

    return $distance;
}

function distanceFunctional(string $strandA, string $strandB) : int
{
    if (strlen($strandA) !== strlen($strandB)) {
        throw new \InvalidArgumentException('DNA strands must be of equal length.');
    }

    if ($strandA === $strandB) {
        return 0;
    }

    return array_reduce(
        array_keys(array_fill(0, strlen($strandA), 0)),
        function ($distance, $key) use ($strandA, $strandB) {
            if ($strandA[$key] !== $strandB[$key]) {
                $distance++;
            }

            return $distance;
        },
        0
    );
}

function distanceFunctional2(string $strandA, string $strandB) : int
{
    if (strlen($strandA) !== strlen($strandB)) {
        throw new \InvalidArgumentException('DNA strands must be of equal length.');
    }

    if ($strandA === $strandB) {
        return 0;
    }

    return count(array_diff_assoc(str_split($strandA), str_split($strandB)));
}

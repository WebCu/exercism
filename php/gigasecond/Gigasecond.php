<?php

const GIGASECOND = 1e+9;

function from(DateTimeImmutable $date)
{
    return $date->add(new DateInterval('PT' . GIGASECOND . 'S'));
}
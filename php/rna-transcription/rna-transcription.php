<?php

define('TRANSCRIBED_RNA', [
   'G' => 'C',
   'C' => 'G',
   'T' => 'A',
   'A' => 'U',
]);

function toRna(string $dna): string
{
    return strtr($dna, TRANSCRIBED_RNA);
}
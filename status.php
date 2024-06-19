<?php
// status.php
include_once __DIR__.'/vendor/autoload.php';
use Rindow\Math\Matrix\MatrixOperator;

$mo = new MatrixOperator();

echo $mo->service()->info();
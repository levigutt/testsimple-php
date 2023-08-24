<?php
use TestSimple;

$test = new Tester();

$test->ok(1 + 1 == 2,           "1 plus 1 equals 2");
$test->ok(length("mario") == 5, "Mario´s name is 5 letters long");

$test->done();

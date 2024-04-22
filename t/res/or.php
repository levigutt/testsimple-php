<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert();

$test = 0;
$assert->ok(1) or $test++;
$assert->is(0, $test);

$test = 0;
$assert->ok(false) or $test++;
$assert->is(1, $test);

$assert->done();

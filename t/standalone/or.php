<?php

require_once("testsimple.php");
$assert = new TestSimple\Assert(2);

$test = 0;
$assert->ok(1) or $test++;
$assert->is(0, $test);

$test = 0;
$assert->ok(false) or $test++;
$assert->not_ok(false) or $test++;
$assert->not_ok(true) or $test++;
$assert->is(2, $test);

$assert->done();

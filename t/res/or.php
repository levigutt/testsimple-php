<?php

require_once("testsimple.php");
$assert = new TestSimple\Assert(plan: 2, output: 'dot');

$test = 0;
$assert->ok(1) or $test++;
$assert->is(0, $test);

$test = 0;
$assert->ok(false) or $test++;
$assert->is(1, $test);

$assert->done();
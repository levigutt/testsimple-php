<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 4, output: 'tap');

$assert->ok(true);
$assert->ok(true);
$assert->ok(false);
$assert->ok(true);

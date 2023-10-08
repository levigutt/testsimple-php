<?php
require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 4, output: 'tap');


$assert->ok(false, "should fail on line 2 of t/prove/02-test.php");

$assert->ok(false, "should fail on line 4 of t/prove/02-test.php");

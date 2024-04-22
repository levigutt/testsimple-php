<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert(plan: 4);

// should pass
$assert->is(5, 5,   "is: strict equality passes");
$assert->is(5, fn() => 5, "is: can test callable return value");

// should fail
$assert->is(5, "5", "is: loose equality fails");
$assert->is(1, 0, "is: not equal");


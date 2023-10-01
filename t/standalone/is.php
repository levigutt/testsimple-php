<?php

require_once("testsimple.php");
$assert = new TestSimple\Assert(2);

// should pass
$assert->is(5, 5,   "is: strict equality passes");
$assert->is(5, fn() => 5, "is: can test callable return value");

// should fail
$assert->is(5, "5", "is: loose equality fails");
$assert->is(1, 0, "is: not equal");

$assert->done();

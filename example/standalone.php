<?php

require_once("testsimple.php");
$test = new TestSimple\Tester();

$test->ok(false);
$test->ok(1 + 1 == 2,     "1 plus 1 equals 2"); # optional description
$test->insist(2 + 2 == 5, "2 plus 2 equals 5"); # stop execution on failure
$test->ok(42%5);
$test->done();

?>

<?php

require_once("testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(false);
$assert->ok(1 + 1 == 2,     "1 plus 1 equals 2"); # optional description
$assert->insist(2 + 2 == 5, "2 plus 2 equals 5"); # stop execution on failure
$assert->ok(42%5);
$assert->done();

?>

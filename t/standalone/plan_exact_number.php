<?php 

require_once("testsimple.php");
$assert = new TestSimple\Assert(4);

$assert->ok(true);
$assert->ok(true);
$assert->ok(true);
$assert->ok(true);

$assert->done();

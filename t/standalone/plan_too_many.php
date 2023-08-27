<?php 

require_once("testsimple.php");
$assert = new TestSimple\Assert(10);

$assert->ok(true);
$assert->ok(true);
$assert->ok(true);
$assert->ok(true);

$assert->done();

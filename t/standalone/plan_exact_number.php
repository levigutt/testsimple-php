<?php 

require_once("testsimple.php");
$test = new TestSimple\Tester(4);

$test->ok(true);
$test->ok(true);
$test->ok(true);
$test->ok(true);

$test->done();

<?php 

require_once("testsimple.php");
$test = new TestSimple\Tester(2);

$test->ok(true);
$test->ok(true);
$test->ok(true);
$test->ok(true);

$test->done();

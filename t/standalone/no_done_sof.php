<?php

require_once "testsimple.php";
$test = new TestSimple\Tester();

$test->stop_on_failure = true;
$test->ok(true);

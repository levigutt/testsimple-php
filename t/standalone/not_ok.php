<?php

require_once "testsimple.php";
$assert = new TestSimple\Assert();

$assert->not_ok(false, "should pass");
$assert->not_ok(true,  "should fail");

$assert->done();

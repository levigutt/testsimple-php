<?php

require_once "testsimple.php";
$assert = new TestSimple\Assert();

$assert->insist(false);
$assert->ok(false, "this should not run");

$assert->done();

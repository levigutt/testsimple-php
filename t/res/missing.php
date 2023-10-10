<?php

require_once "testsimple.php";
$assert = new TestSimple\Assert(plan: 10);

$assert->ok(true);

$assert->done();

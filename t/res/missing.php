<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 10);

$assert->ok(true);

$assert->done();

<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert(plan: 3);

$assert->ok(true);
$assert->ok(true);
$assert->ok(true);
$assert->ok(true);

$assert->done();


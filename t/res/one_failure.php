<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 4);

$assert->ok(true, "a successful test");
$assert->ok(true, "truth wins");
$assert->ok(false, "lies");
$assert->ok(true, "yes");

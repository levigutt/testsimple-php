<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 4);

$assert->ok(true);
$assert->ok(true, "name of passing test");
$assert->ok(false);
$assert->ok(false, "name of failing test");

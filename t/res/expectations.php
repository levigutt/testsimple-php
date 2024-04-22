<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert(plan: 4);

$assert->is(5, "5", "int vs string");
$assert->is(1, 0,   "wrong value");
$assert->is(new Exception(), fn() => throw new Error(), "wrong error type");
$assert->is(new Error('expected'), fn() => throw new Error('got'), "wrong error message");


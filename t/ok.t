#!/usr/bin/php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 5);

$assert->ok(true, "true should pass");
$assert->ok(1,     "1 should pass");
$assert->ok("a",   "a should pass");
$assert->ok(['a'], "['a'] should pass");
$assert->ok(['0'], "['0'] should pass");

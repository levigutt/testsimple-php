#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
require_once "t/lib/test-parser.php";
$assert = new TestSimple\Assert(plan: 5);

$result = exec("php t/res/is.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("1..4", $parsed[0]['test']);
$assert->is("ok 1", $parsed[1]['test']);
$assert->is("ok 2", $parsed[2]['test']);
$assert->is("not ok 3", $parsed[3]['test']);
$assert->is("not ok 4", $parsed[4]['test']);



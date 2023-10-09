#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
require_once "t/lib/test-parser.php";
$assert = new TestSimple\Assert();

$result = exec("php t/res/plan_prepare.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("1..4", $parsed[0]['test'], "reporting of number of tests");
$assert->is("ok 1", $parsed[1]['test']);
$assert->is("ok 2", $parsed[2]['test']);
$assert->is("ok 3", $parsed[3]['test']);
$assert->is("ok 4", $parsed[4]['test']);
$assert->is(false, isset($parsed[5]));

unset($out);
$result = exec("php t/res/plan_done.php", $out, $retval);
$parsed = parse_output($out);
$test_count = count(array_filter(  $parsed
                                ,  fn($x) => str_starts_with($x['test'],'ok')
                                )
                   );
$assert->is("1..$test_count", $parsed[count($parsed)-1]['test'],
    "reporting of number of tests");

$assert->done();

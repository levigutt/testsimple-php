#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
require_once "t/lib/test-parser.php";
$assert = new TestSimple\Assert(plan: 19);

$result = exec("php t/res/all_ok.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("ok 1", $parsed[0]['test']);
$assert->is("ok 2", $parsed[1]['test']);
$assert->is("ok 3", $parsed[2]['test']);
$assert->is("ok 4", $parsed[3]['test']);
$assert->is("1..4", $parsed[4]['test'],
    "reporting of number of tests");
$assert->is(0, $retval, "successful exits with 0");

unset($out);
$result = exec("php t/res/one_failure.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("ok 1",     $parsed[1]['test']);
$assert->is("a successful test", $parsed[1]['desc'],
    "description is printed along with test result");
$assert->is("ok 2",     $parsed[2]['test']);
$assert->is("truth wins", $parsed[2]['desc']);
$assert->is("not ok 3", $parsed[3]['test']);
$assert->is("lies", $parsed[3]['desc'],
    "description is printed along with test result for failed test");
$assert->is("ok 4",     $parsed[4]['test']);
$assert->is("Looks like you failed 1 out of 4 tests", $out[array_key_last($out)],
    "confirm error messag for failed run");
$assert->is(1, $retval, "failed run has exit code");

unset($out);
$result = exec("php t/res/trace1.php", $out, $retval);
$parsed = parse_output($out);
$assert->ok(array_filter(  $parsed[1]['diag']
                        ,  fn($x) => str_ends_with($x,"t/res/trace1.php:9")
                        ), "output contains trace");
$assert->ok(array_filter(  $parsed[2]['diag']
                        ,  fn($x) => str_ends_with($x,"t/res/trace1.php:13")
                        ), "output contains trace");

unset($out);
$result = exec("php t/res/trace2.php", $out, $retval);
$parsed = parse_output($out);
$assert->ok(array_filter(  $parsed[1]['diag']
                        ,  fn($x) => str_ends_with($x, "t/res/trace2.php:6")
                        ), "output contains trace2");
$assert->ok(array_filter(  $parsed[2]['diag']
                        ,  fn($x) => str_ends_with($x, "t/res/trace2.php:8")
                        ), "output contains trace2");

#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
require_once "t/lib/test-parser.php";
$assert = new TestSimple\Assert();

$result = exec("php t/res/named_unnamed.php", $out, $retval);
$parsed = parse_output($out);
$assert->is(2, $retval, "should have two failures");
$assert->is("ok 1", $parsed[1]['test'], "first test passes");
$assert->is('', $parsed[1]['desc'],
    "unnamed passing test has no description");
$assert->is("ok 2", $parsed[2]['test']);
$assert->is("name of passing test", $parsed[2]['desc'],
    "passing test has correct name");
$assert->is("not ok 3", $parsed[3]['test'], "third test fails");
$assert->is('', $parsed[3]['desc'],
    "unnamed failing test has no description");
$assert->is("#\tFailed test at t/res/named_unnamed.php:8", $parsed[3]['diag'][0],
    "unnamed test shows no name");
$assert->is("not ok 4", $parsed[4]['test']);
$assert->is("#\tFailed test 'name of failing test'", $parsed[4]['diag'][0],
    "named test shows name");
$assert->is("#\tat t/res/named_unnamed.php:9", $parsed[4]['diag'][1],
    "named trace on separate line");

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

unset($out);
$expected = <<<EOF
1..4
not ok 1 - int vs string
#	Failed test 'int vs string'
#	at t/res/expectations.php:6
#	expected: <5>
#	     got: <5>
not ok 2 - wrong value
#	Failed test 'wrong value'
#	at t/res/expectations.php:7
#	expected: <1>
#	     got: <0>
not ok 3 - wrong error type
#	Failed test 'wrong error type'
#	at t/res/expectations.php:8
#	expected: Exception('')
#	     got: Error('')
not ok 4 - wrong error message
#	Failed test 'wrong error message'
#	at t/res/expectations.php:9
#	expected: Error('expected')
#	     got: Error('got')
Looks like you failed 4 out of 4 tests
EOF;
$expected_out = explode("\n", $expected);
$result = exec("php t/res/expectations.php", $got_out, $retval);
foreach($expected_out as $index => $line)
    $assert->is($line, $got_out[$index]);

$assert->done();

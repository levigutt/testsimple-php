#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert();

$tests = [];

$expected = <<<EOL
1..4
ok 1
ok 2 - name of passing test
not ok 3
#	Failed test at t/res/named_unnamed.php:8
not ok 4 - name of failing test
#	Failed test 'name of failing test'
#	at t/res/named_unnamed.php:9
Looks like you failed 2 out of 4 tests
EOL;
$tests[] = [  'script'        => 'php t/res/named_unnamed.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 2
           ,  'name'          => 'output name when present, or trace on same line'
           ];


$expected = <<<EOL
1..4
ok 1 - a successful test
ok 2 - truth wins
not ok 3 - lies
#	Failed test 'lies'
#	at t/res/one_failure.php:8
ok 4 - yes
Looks like you failed 1 out of 4 tests
EOL;
$tests[] = [  'script'        => 'php t/res/one_failure.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 1
           ,  'name'          => 'single failure is reported correctly, with trace'
           ];

unset($got_out);
$expected = <<<EOF
1..4
not ok 1 - int vs string
#	Failed test 'int vs string'
#	at t/res/expectations.php:6
#	expected: integer(5)
#	     got: string(1) "5"
not ok 2 - wrong value
#	Failed test 'wrong value'
#	at t/res/expectations.php:7
#	expected: integer(1)
#	     got: integer(0)
not ok 3 - wrong error type
#	Failed test 'wrong error type'
#	at t/res/expectations.php:8
#	expected: Exception("")
#	     got: Error("")
not ok 4 - wrong error message
#	Failed test 'wrong error message'
#	at t/res/expectations.php:9
#	expected: Error("expected")
#	     got: Error("got")
Looks like you failed 4 out of 4 tests
EOF;
$tests[] = [  'script'        => 'php t/res/expectations.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 4
           ,  'name'          => 'outputs expected and actual value correctly'
           ];

$expected = <<<EOL
1..4
ok 1 - is: strict equality passes
ok 2 - is: can test callable return value
not ok 3 - is: loose equality fails
#	Failed test 'is: loose equality fails'
#	at t/res/is.php:11
#	expected: integer(5)
#	     got: string(1) "5"
not ok 4 - is: not equal
#	Failed test 'is: not equal'
#	at t/res/is.php:12
#	expected: integer(1)
#	     got: integer(0)
Looks like you failed 2 out of 4 tests
EOL;
$tests[] = [  'script'        => 'php t/res/is.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 2
           ,  'name'          => '`is` has strict comparison and validates properly'
           ];


$expected = <<<EOL
1..4
not ok 1
#	Failed test at t/res/array.php:7
#	expected: [integer(1),integer(2),integer(3)]
#	     got: [integer(3),integer(2),integer(1)]
not ok 2
#	Failed test at t/res/array.php:8
#	expected: [integer(1),integer(2),integer(3)]
#	     got: [string(1) "a",string(1) "b"]
not ok 3
#	Failed test at t/res/array.php:9
#	expected: [[integer(1)],integer(2),integer(3)]
#	     got: [integer(3),[integer(2)],integer(1)]
not ok 4
#	Failed test at t/res/array.php:10
#	expected: [string(6) "robert",string(3) "bob"]
#	     got: [string(5) "frank",string(7) "francis"]
Looks like you failed 4 out of 4 tests
EOL;
$tests[] = [  'script'        => 'php t/res/array.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 4
           ,  'name'          => 'failed `is` on arrays shows human readable diff'
           ];

foreach($tests as $test)
{
    unset($got);
    exec($test['script'], $got, $retval);
    $expected = explode("\n", $test['expected_out']);
    $assert->is($test['expected_exit'], $retval, sprintf("%s (exit code)", $test['name']));
    $lines_match = true;
    foreach($expected as $index => $line)
        if( !$assert->is($line, $got[$index], sprintf("line %d matches", $index+1)) )
        {
            $lines_match = false;
            break;
        }
    $assert->ok($lines_match, sprintf("%s (output)", $test['name']));
}

###########################################
###### EXIT CODES AND RESULT SUMMARY ######
###########################################

$last_line = exec("php t/res/min.php", $out, $retval);
$assert->is(255, $retval,
    "empty test run is considered a failure");

$last_line = exec("php t/res/max.php", $out, $retval);
$assert->is("Looks like you failed 300 out of 300 tests", $last_line, 
    "result summary has correct failure count beyond limit of exit code");
$assert->is(254, $retval,
    "exit code stops at 254 despite more failures");

$last_line = exec("php t/res/some_failures.php", $out, $retval);
$assert->is("Looks like you failed 4 out of 13 tests", $last_line,
    "result summary has correct failure count");
$assert->is(4, $retval,
    "four failures has exit code 4");

$last_line = exec("php t/res/missing.php", $out, $retval);
$assert->is("Looks like you failed 9 out of 1 tests", $last_line,
    "result summary has correct failure count for missing tests");
$assert->is(9, $retval,
    "missing tests count as failed tests");

$assert->done();

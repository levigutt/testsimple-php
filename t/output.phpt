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
$tests[] = [  'script'        => 'php t/res/expectations.php'
           ,  'expected_out'  => $expected
           ,  'expected_exit' => 4
           ,  'name'          => 'outputs expected and actual value correctly'
           ];


foreach($tests as $test)
{
    unset($got);
    exec($test['script'], $got, $retval);
    $expected = explode("\n", $test['expected_out']);
    $assert->is($test['expected_exit'], $retval, sprintf("%s (exit code)", $test['name']));
    $assert->ok(function() use ($expected, $got) {
        foreach($expected as $index => $line)
            if( $line !== $got[$index] )
                return false;
        return true;
    }, sprintf("%s (output)", $test['name']));
}


$assert->done();

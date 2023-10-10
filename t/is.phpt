#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
require_once "t/lib/test-parser.php";
$assert = new TestSimple\Assert();

$result = exec("php t/res/is.php", $got_out, $retval);
$expected = <<<EOL
1..4
ok 1 - is: strict equality passes
ok 2 - is: can test callable return value
not ok 3 - is: loose equality fails
#	Failed test 'is: loose equality fails'
#	at t/res/is.php:11
#	expected: <5>
#	     got: <5>
not ok 4 - is: not equal
#	Failed test 'is: not equal'
#	at t/res/is.php:12
#	expected: <1>
#	     got: <0>
Looks like you failed 2 out of 4 tests
EOL;
$expected_out = explode("\n", $expected);

foreach($expected_out as $index => $line)
    $assert->is($line, $got_out[$index]);

$assert->done();

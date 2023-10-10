#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert();

$result = exec("php t/res/plan_prepare.php", $out, $retval);
$expected_test_count = (int)explode('..', $out[0])[1];
$test_count = 0;
foreach($out as $line)
    if( false !== strpos($line, "ok") )
        $test_count++;
$assert->is($expected_test_count, $test_count,
    "plan matches test count");
$assert->is(false, isset($out[$expected_test_count+1]),
    "no more tests than reported");

unset($out);
$result = exec("php t/res/plan_done.php", $out, $retval);
$test_count = 0;
foreach($out as $line)
    if( false !== strpos($line, "ok") )
        $test_count++;
$assert->is("1..$test_count", $out[array_key_last($out)],
    "reported number of tests matches count");

$assert->done();

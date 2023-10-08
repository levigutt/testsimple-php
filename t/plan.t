#!/usr/bin/env php
<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert();

unset($out);
$result = exec("php t/res/plan_prepare.php", $out, $retval);
$assert->is("1..4", $out[0], "reporting of number of tests");
$assert->ok(0 === strpos($out[1],  "ok 1"));
$assert->ok(0 === strpos($out[2],  "ok 2"));
$assert->ok(0 === strpos($out[3],  "ok 3"));
$assert->ok(0 === strpos($out[4],  "ok 4"));
$assert->is(false, isset($out[5]));

unset($out);
$result = exec("php t/res/plan_done.php", $out, $retval);
$test_count = 0;
foreach($out as $line)
    if( 0 === strpos($line, "ok ") )
        $test_count++;

$assert->is("1..$test_count", $line, "reporting of number of tests");

$assert->done();

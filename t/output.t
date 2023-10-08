#!/usr/bin/env php
<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert(plan: 16);

unset($out);
$result = exec("php t/res/all_ok.php", $out, $retval);
$assert->ok(0 === strpos($out[0],  "ok 1"));
$assert->ok(0 === strpos($out[1],  "ok 2"));
$assert->ok(0 === strpos($out[2],  "ok 3"));
$assert->ok(0 === strpos($out[3],  "ok 4"));
$assert->is("1..4", $out[4], "reporting of number of tests");
$assert->is(0, $retval, "successful exits with 0");

unset($out);
$result = exec("php t/res/one_failure.php", $out, $retval);
$assert->ok(0 === strpos($out[1],  "ok 1"));
$assert->ok(0 === strpos($out[2],  "ok 2"));
$assert->ok(0 === strpos($out[3],  "not ok 3"));
$assert->ok(0 === strpos($out[7],  "ok 4"));
$assert->is("Looks like you failed 1 out of 4 tests", $out[array_key_last($out)],
    "confirm error messag for failed run");
$assert->is(1, $retval, "failed run has exit code");

unset($out);
$result = exec("php t/res/trace1.php", $out, $retval);
$assert->ok(str_ends_with($out[3], "t/res/trace1.php:9"),
    "output contains trace");
$assert->ok(str_ends_with($out[7], "t/res/trace1.php:13"),
    "output contains trace");

unset($out);
$result = exec("php t/res/trace2.php", $out, $retval);
$assert->ok(str_ends_with($out[3], "t/res/trace2.php:6"),
    "output contains trace2");
$assert->ok(str_ends_with($out[7], "t/res/trace2.php:8"),
    "output contains trace2");

#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 4);

exec("php t/res/min.php", $out, $retval);
$assert->is(255, $retval,
    "empty test run is considered a failure");

exec("php t/res/max.php", $out, $retval);
$assert->is(254, $retval,
    "exit code stops at 254 despite more failures");

exec("php t/res/some_failures.php", $out, $retval);
$assert->is(4, $retval,
    "one failure has exit code 1");

exec("php t/res/missing.php", $out, $retval);
$assert->is(9, $retval,
    "missing tests count as failed tests");

#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
use function TestSimple\{plan, ok, is, done_testing};

plan(7);

ok(1,       "ok");
is(4, 2+2,  "is");

$result = exec("php t/res/non-oo-fail.php", $out, $retval);
is(1, $retval,              "can fail");

unset($out);
$result = exec("php t/res/non-oo-plan.php", $out, $retval);
$expected_test_count = (int)explode('..', $out[0])[1];
is(2, $expected_test_count, "plan sets correct expectation");
is(0, $retval,              "no failure on successful plan");

unset($out);
$result = exec("php t/res/non-oo-plan-fail.php", $out, $retval);
$expected_test_count = (int)explode('..', $out[0])[1];
is(2, $expected_test_count, "failed plan sets correct expectation");
is(1, $retval,              "failed plan reports a failure");

done_testing();

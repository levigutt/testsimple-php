<?php

$assert->plan+= 11;

$result = exec("./prove.php t/exception", $out, $retval);
$assert->ok(1 == $retval, "exceptions stops execution");
$assert->ok(false !== strpos($result, "FAIL (0 assertions, 1 failures)"),
    "exception counts as failure, but not as assertion");

$result = exec("php t/standalone/insist.php", $out, $retval);
$assert->ok(1 == $retval, "'insist' stops on failure");
$assert->ok(false !== strpos($result, "FAIL (1 assertions, 1 failures)"),
    "failed insist counts as both failure and assertion");

$result = exec("php t/standalone/plan_too_few.php", $out, $retval);
$assert->ok(1 == $retval, "too few tests");

$result = exec("php t/standalone/plan_too_many.php", $out, $retval);
$assert->ok(1 == $retval, "too many tests");

$result = exec("php t/standalone/plan_exact_number.php", $out, $retval);
$assert->ok(0 == $retval, "exact number of tests");

$result = exec("php t/standalone/no_done.php", $out, $retval);
$assert->ok(255 == $retval, "tests fail unless ->done() is called");

$result = exec("php t/standalone/no_done_sof.php", $out, $retval);
$assert->ok(255 == $retval, "tests fail unless ->done() is called, even if stop-on-failure is set");

$result = exec("php t/standalone/max.php", $out, $retval);
$assert->ok(254 == $retval, "more than 254 failed tests are reported as 254");

$test_file_order++;
$assert->ok(3 == $test_file_order, "file #3 comes third");

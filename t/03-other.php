<?php

$assert->plan+= 17;

$result = exec("./prove.php t/exception", $out, $retval);
$assert->ok(1 == $retval, "exceptions stops execution");
$assert->ok(false !== strpos($result, "FAIL (0 assertions, 1 failures)"),
    "exception counts as failure, but not as assertion");
$assert->ok(false !== strpos(join('',$out), "/t/exception/throw_error.php:3"),
    "exceptions shows file and line number");

unset($out);
$result = exec("php t/standalone/insist.php", $out, $retval);
$assert->ok(1 == $retval, "'insist' stops on failure");
$assert->ok(false !== strpos($result, "FAIL (1 assertions, 1 failures)"),
    "failed insist counts as both failure and assertion");

unset($out);
$result = exec("php t/standalone/plan_too_few.php", $out, $retval);
$assert->ok(1 == $retval, "too few tests");

unset($out);
$result = exec("php t/standalone/plan_too_many.php", $out, $retval);
$assert->ok(1 == $retval, "too many tests");

unset($out);
$result = exec("php t/standalone/plan_exact_number.php", $out, $retval);
$assert->ok(0 == $retval, "exact number of tests");

unset($out);
$result = exec("php t/standalone/no_done.php", $out, $retval);
$assert->ok(255 == $retval, "tests fail unless ->done() is called");

unset($out);
$result = exec("php t/standalone/no_done_sof.php", $out, $retval);
$assert->ok(255 == $retval, "tests fail unless ->done() is called, even if stop-on-failure is set");

unset($out);
$result = exec("php t/standalone/max.php", $out, $retval);
$assert->ok(254 == $retval, "more than 254 failed tests are reported as 254");

unset($out);
$result = exec("php t/standalone/not_ok.php", $out, $retval);
$assert->ok(1 == $retval, "true val to not_ok should fail");
$assert->ok(false !== strpos($result, "FAIL (2 assertions, 1 failures)"),
    "failed not_ok causes a failure");

unset($out);
$result = exec("php prove.php t/trace", $out, $retval);
$assert->ok(str_ends_with($out[2], "t/trace/01-test.php:3"));
$assert->ok(str_ends_with($out[5], "t/trace/01-test.php:5"));
$assert->ok(str_ends_with($out[8], "t/trace/02-test.php:2"));
$assert->ok(str_ends_with($out[11], "t/trace/02-test.php:4"));

$test_file_order++;
$assert->ok(3 == $test_file_order, "file #3 comes third");

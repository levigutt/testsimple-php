<?php

$assert->plan+= 15;

unset($out);
$result = exec("./prove.php t/all_ok", $out, $retval);
$assert->is('.....', $out[0], "successful run reports dots");
$assert->is("\033[92mOK (5 assertions)\033[0m", $out[1],
    "confirm output for successful run, with color");
$assert->is(0, $retval, "successful exits with 0");

unset($out);
$result = exec("./prove.php t/one_failure", $out, $retval);
$assert->is('...F.', $out[0], "failed run reports failures as F");
$assert->is("\033[91mFAIL (5 assertions, 1 failures)\033[0m", $out[array_key_last($out)],
    "confirm output for failed run, with color");
$assert->is(1, $retval, "failed run has exit code");

unset($out);
$result = exec("php t/standalone/max.php", $out, $retval);
$assert->is(254, $retval, "more than 254 failed tests are reported as 254");

unset($out);
$result = exec("php prove.php t/trace", $out, $retval);
$assert->ok(str_ends_with($out[2], "t/trace/01-test.php:3"));
$assert->ok(str_ends_with($out[5], "t/trace/01-test.php:5"));
$assert->ok(str_ends_with($out[8], "t/trace/02-test.php:2"));
$assert->ok(str_ends_with($out[11], "t/trace/02-test.php:4"));

unset($out);
$result = exec("./prove.php t/exception", $out, $retval);
$assert->is('E', substr($out[0], strlen($out[0])-1, 1), "exceptions are tallied as 'E'");
$assert->ok(false !== strpos($result, "FAIL (10 assertions, 5 failures)"),
    "exception counts as failure, but not as assertion");
$assert->ok($result !== strpos($result, "/t/exception/throw_error.php:3"),
    "exceptions shows file and line number");
$assert->is(5, $retval, "exit code signified number of failures");

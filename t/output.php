<?php

$assert->plan+= 8;

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
$assert->is('E', $out[0], "exceptions are tallied as 'E'");
$assert->ok(false !== strpos($result, "FAIL (0 assertions, 1 failures)"),
    "exception counts as failure, but not as assertion");
$assert->ok($result !== strpos($result, "/t/exception/throw_error.php:3"),
    "exceptions shows file and line number");

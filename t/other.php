<?php

# 4 tests

$result = exec("./prove.php t/insist", $out, $retval);
$test->ok(1 == $retval, "'insist' stops on failure");
$test->ok(false !== strpos($result, "FAIL (1 assertions, 1 failures)"),
    "failed insist counts as both failure and assertion");

$result = exec("./prove.php t/exception", $out, $retval);
$test->ok(1 == $retval, "exceptions stops execution");
$test->ok(false !== strpos($result, "FAIL (0 assertions, 1 failures)"),
    "exception counts as failure, but not as assertion");

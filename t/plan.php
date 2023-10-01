<?php

$assert->plan+= 3;

unset($out);
$result = exec("php t/standalone/plan_too_few.php", $out, $retval);
$assert->is(1, $retval, "too few tests");

unset($out);
$result = exec("php t/standalone/plan_too_many.php", $out, $retval);
$assert->is(1, $retval, "too many tests");

unset($out);
$result = exec("php t/standalone/plan_exact_number.php", $out, $retval);
$assert->is(0, $retval, "exact number of tests");


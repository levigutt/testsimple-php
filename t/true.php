<?php

$test->plan_count+= 5;

$test->ok(true, "true should pass");
$test->ok(1,     "1 should pass");
$test->ok("a",   "a should pass");
$test->ok(['a'], "['a'] should pass");
$test->ok(['0'], "['0'] should pass");

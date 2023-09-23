<?php

$assert->plan+= 6;

$assert->ok(true, "true should pass");
$assert->ok(1,     "1 should pass");
$assert->ok("a",   "a should pass");
$assert->ok(['a'], "['a'] should pass");
$assert->ok(['0'], "['0'] should pass");

$test_file_order??= 1;
$assert->ok(1 == $test_file_order, "file #1 comes first");

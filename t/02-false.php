<?php

$assert->plan+= 7;

$assert->not_ok(false, "not false should pass");
$assert->not_ok(0,     "not 0 should pass");
$assert->not_ok(null,  "not null should pass");
$assert->not_ok('',    "not '' should pass");
$assert->not_ok([],    "not [] should pass");

$test_file_order++;
$assert->ok(2 == $test_file_order, "file #2 comes second");

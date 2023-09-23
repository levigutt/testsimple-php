<?php

$assert->plan+= 6;

$assert->not_ok(false, "false should fail");
$assert->not_ok(0,     "0 should fail");
$assert->not_ok(null,  "null should fail");
$assert->not_ok('',    "'' should fail");
$assert->not_ok([],    "[] should fail");

$test_file_order++;
$assert->ok(2 == $test_file_order, "file #2 comes second");

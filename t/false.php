<?php

$test->plan+= 5;

$test->not_ok(false, "false should fail");
$test->not_ok(0,     "0 should fail");
$test->not_ok(null,  "null should fail");
$test->not_ok('',    "'' should fail");
$test->not_ok([],    "[] should fail");

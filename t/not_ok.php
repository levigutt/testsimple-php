<?php

$assert->plan+= 5;

$assert->not_ok(false, "false should fail");
$assert->not_ok(0,     "0 should fail");
$assert->not_ok(null,  "null should fail");
$assert->not_ok('',    "'' should fail");
$assert->not_ok([],    "[] should fail");

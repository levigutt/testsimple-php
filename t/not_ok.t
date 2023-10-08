#!/usr/bin/env php
<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert();

$result = exec("php t/res/false.php", $out, $retval);
$assert->is("1..9", $out[0], "reporting of number of tests");
$assert->is(9, $retval, "false test should return all false");

$assert->done();

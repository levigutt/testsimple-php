#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 2);

exec("php t/res/no_done.php", $out, $retval);
$assert->is(255, $retval, "tests fail unless ->done() is called");

exec("php t/res/no_done_sof.php", $out, $retval);
$assert->is(255, $retval, "tests fail unless ->done() is called, even if stop-on-failure is set");


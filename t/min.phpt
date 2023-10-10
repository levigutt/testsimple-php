#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 1);

exec("php t/res/min.php", $out, $retval);
$assert->is(255, $retval,
    "empty test run is considered a failure");

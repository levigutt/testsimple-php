#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 1);

exec("php t/res/max.php", $out, $retval);
$assert->is(254, $retval,
    "exit code stops at 254 despite more failures");

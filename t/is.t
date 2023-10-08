#!/usr/bin/php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 1);

unset($out);
$result = exec("php t/res/is.php", $out, $retval);
$assert->is("..FF", $out[0], "failed `assert->is` causes failed run");


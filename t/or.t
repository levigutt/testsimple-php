#!/usr/bin/php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 1);

unset($out);
$result = exec("php t/res/or.php", $out, $retval);
$assert->is("..F.", $out[0], "ok or triggers only on failed test");


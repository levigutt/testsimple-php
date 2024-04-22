<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert();

$assert->stop_on_failure = true;
$assert->ok(true);

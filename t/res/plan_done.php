<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert();

$assert->ok(true);
$assert->ok(true);
$assert->ok(true);

$assert->done();


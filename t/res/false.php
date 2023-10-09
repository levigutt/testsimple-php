<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 9);

$assert->ok(false);
$assert->ok(0);
$assert->ok(null);
$assert->ok('');
$assert->ok('0');
$assert->ok([]);

$assert->ok(function(){ return false; });
$assert->ok(function(){ return 0; });

$assert->is(5, 4);



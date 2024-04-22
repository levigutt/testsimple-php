<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 300);

for($i = 300; $i; $i--)
    $assert->ok(false);

$assert->done();

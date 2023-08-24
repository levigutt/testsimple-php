<?php
require_once "vendor/autoload.php";

use TestSimple\Tester as Tester;
$test = new Tester();

$test->ok(1 + 1 == 2, "1 plus 1 equals 2");

$test->ok(4 == strlen("test"), "TEST is a four letter word");

$test->done();

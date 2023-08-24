<?php
require_once "vendor/autoload.php";

use TestSimple\Tester;
$test = new Tester();

$test->ok(1 + 1 == 2, "1 plus 1 equals 2");

$test->ok(4 == strlen("test"), "TEST is a four letter word");

$test->ok("1" == 1); # description is not required

$test->ok(2 + 2 == 5, "2 plus 2 equals 5");

$test->done();

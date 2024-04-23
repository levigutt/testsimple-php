<?php

require_once("vendor/autoload.php");
use function TestSimple\{plan, ok, done_testing};

plan(2);
ok(1);
ok("yes");

done_testing();

<?php

$assert->plan+= 2;

unset($out);
$result = exec("php t/standalone/no_done.php", $out, $retval);
$assert->is(255, $retval, "tests fail unless ->done() is called");

unset($out);
$result = exec("php t/standalone/no_done_sof.php", $out, $retval);
$assert->is(255, $retval, "tests fail unless ->done() is called, even if stop-on-failure is set");


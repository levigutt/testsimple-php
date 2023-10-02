<?php

$assert->plan+= 2;

unset($out);
$result = exec("./prove.php t/exception", $out, $retval);
$assert->is(".....FFF.E", $out[0]);
$assert->is(4, $retval, "exceptions count as failures and stops execution");


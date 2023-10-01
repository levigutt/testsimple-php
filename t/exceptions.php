<?php

$assert->plan+= 1;

unset($out);
$result = exec("./prove.php t/exception", $out, $retval);
$assert->is(1, $retval, "exceptions stops execution");


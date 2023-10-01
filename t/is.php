<?php

$assert->plan+= 1;

unset($out);
$result = exec("php t/standalone/is.php", $out, $retval);
$assert->is("..FFF", $out[0], "failed `assert->is` causes failed run");


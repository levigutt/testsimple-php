<?php

$assert->plan+= 1;

unset($out);
$result = exec("php t/standalone/or.php", $out, $retval);
$assert->is("..F..FF", $out[0], "ok or triggers only on failed test");

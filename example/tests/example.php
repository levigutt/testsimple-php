<?php
isset( $assert ) or die("Run tests via prove.php\n");

$assert->is(2, 1+1, "1 plus 1 equals 2");

$assert->is(4, strlen("test"), "TEST is a four letter word");

$assert->ok("1" == 1); # description is optional

$assert->is(5, 2+2, "2 plus 2 equals 5");


<?php

if( !$assert )
    die("Run via prove.php\n");

$assert->ok(1 + 1 == 2, "1 plus 1 equals 2");

$assert->ok(4 == strlen("test"), "TEST is a four letter word");

$assert->ok("1" == 1); # description is not required

$assert->ok(2 + 2 == 5, "2 plus 2 equals 5");


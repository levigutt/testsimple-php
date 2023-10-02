<?php
isset( $assert ) or die("Run tests via prove.php\n");

$assert->is(2, 1+1, "basic math works");

$assert->is(4, strlen("TEST"), "TEST is a four letter word");

$assert->is(5, 2+2, "2 plus 2 equals 5");


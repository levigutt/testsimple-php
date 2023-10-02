<?php
isset( $assert ) or die("Run tests via prove.php\n");

$assert->ok("truthy", "absolute truth is not always needed");

$assert->is(false, 5 === "5", "this is strictly false");

$assert->is(new Error(), fn() => dont_exist(), "function should not exist");


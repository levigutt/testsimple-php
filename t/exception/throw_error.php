<?php

$assert->ok(5 / 0, "thrown exception should be reported as E from prove.php");
$assert->ok(false, "SHOULD NOT RUN");

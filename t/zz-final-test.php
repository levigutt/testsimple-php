<?php

$assert->plan+= 1;

$test_file_count = count( array_filter(scandir(__DIR__), fn($f) => str_ends_with($f, '.php')) );

$assert->is($test_file_count, $assert->file_count, "zz-file comes first");

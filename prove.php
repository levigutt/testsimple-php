#!/usr/bin/env php
<?php
require_once "vendor/autoload.php";

use TestSimple\Tester;
$test = new Tester();

# load tests from provided dir, defaults to ./tests
$dir = sprintf("%s/%s", getcwd(), count($argv) > 1 ? $argv[1] : 'tests');
$dh = opendir($dir);
register_shutdown_function(fn() => closedir($dh));

while( $file = readdir($dh) )
{
    if( !is_file("$dir/$file") || substr($file, -4) != ".php")
        continue;
    # catch exceptions for each file
    try
    {
        require_once "$dir/$file";
    }
    catch( Throwable $e)
    {
        $test->fail(sprintf("Caught exception: %s", $e->getMessage()), "E");
    }
}

$test->done();

?>

#!/usr/bin/env php
<?php
require_once "vendor/autoload.php";

use TestSimple\Assert;
$assert = new Assert();

# load tests from provided dir, defaults to ./tests
$dir = sprintf("%s/%s", getcwd(), count($argv) > 1 ? $argv[1] : 'tests');
$dh = opendir($dir);
register_shutdown_function(fn() => closedir($dh));

$files = [];
while( $file = readdir($dh) )
    if( is_file("$dir/$file") && substr($file, -4) == ".php" )
        $files[] = $file;

asort($files);
foreach($files as $file)
{
    $assert->file_count++;
    # catch exceptions for each file
    try
    {
        require_once "$dir/$file";
    }
    catch( Throwable $e)
    {
        $fail_message = sprintf("%s:%s\n\tCaught exception: %s\n",
                            $e->getFile(),
                            $e->getLine(),
                            $e->getMessage(),
                        );
        $assert->fail($fail_message, "E");
    }
}

$assert->done();

?>

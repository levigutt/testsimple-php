#!/usr/bin/env php
<?php
require_once(dirname(__FILE__)."/../vendor/autoload.php");
use function TestSimple\{ok, is, done_testing};

ok(true);

is(2, 1+1, "basic math works");

ok(function()
{
    $c = new Thing();
    return $c->run();
}, "thing can run");

done_testing();



class Thing
{
    public function __construct(public int $speed){}
    public function run() : string
    {
        return sprintf("running at %d km/h", $this->speed);
    }
}
?>

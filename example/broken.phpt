#!/usr/bin/env php
<?php
//require_once("vendor/autoload.php"); // load via composer
require_once(dirname(__FILE__)."/../testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(true);

$assert->is(2, 1+1, "basic math works");

$assert->ok(function()
{
    $c = new Thing();
    return $c->run();
}, "thing can run");

$assert->done();



class Thing
{
    public function __construct(public int $speed){}
    public function run() : string
    {
        return sprintf("running at %d km/h", $this->speed);
    }
}
?>

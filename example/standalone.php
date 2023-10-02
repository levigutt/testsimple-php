<?php
require_once("testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(get_data());

$assert->is(2, 1+1, "basic math works");

$assert->is(new ArgumentCountError(), function()
{
    $c = new Thing();
    return $c->run();
}, "thing cannot run without speed");

$assert->ok(function()
{
    $c = new Thing(5);
    return $c->run();
}, "thing can run");

$assert->done();


function get_data(){return 5;}
class Thing
{
    public function __construct(public int $speed){}
    public function run() : int
    {
        return $this->speed;
    }
}
?>

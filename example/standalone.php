<?php
require_once("testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(false); # description is optional
$assert->is(2, 1+1, "basic math works");
$assert->ok(function()
{
    $c = count(get_stuff());
    return do_stuff($c);
}, "do_stuff should return true");

$assert->done();


function get_stuff(){ return [0]; }
function do_stuff(int $c){ return 42/$c; }
?>

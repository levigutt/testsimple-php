<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert();

class CustomThrowable extends \Exception
{
    protected $message = 'Custom throwable';
}

$assert->ok(function(){ 1 /0; }, 'Thrown error');
$assert->ok(function(){ throw new \Error('Custom error'); });
$assert->ok(function(){ throw new \CustomThrowable("Threw me for a loop\nmultiline error message"); });

$assert->done();

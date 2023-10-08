#!/usr/bin/php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 5);

$assert->ok(function(){
    return true;
}, "anonymous functions returning true");

$assert->is(false, function(){
    return false;
}, "anonymous functions returning false");

$assert->is(new Error(), function(){
    return 5 / 0;
}, "anonymous functions traps exceptions");

$assert->ok(fn() => true, "passing arrow functions");

$assert->is("abc", fn() => "abc", "test return value of arrow function");

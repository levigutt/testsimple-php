<?php

$assert->plan+= 4;

$assert->ok(function(){
    return true;
}, "anonymous functions returning true");

$assert->not_ok(function(){
    return false;
}, "anonymous functions returning false");

$assert->not_ok(function(){
    return 5 / 0;
}, "anonymous functions traps exceptions");

$assert->ok(fn() => true, "passing arrow functions");

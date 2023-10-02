<?php

$assert->is(new Exception(), function()
{
    throw new Exception('test of exeception');
}, "simple exception test");

$assert->is(new Exception('test of exception'), function()
{
    throw new Exception('test of exception');
}, "exception message must match exactely");

$assert->is(new DivisionByZeroError(), function()
{
    return 6 / 0;
}, "catches and compares correctly specific exception types");

$assert->is('value', function()
{
    $val = 'value';
    $math = 6 / 0;
    return $val;
}, "compare value to exception should fail");

$assert->is(new Exception('mismatch'), function()
{
    throw new Exception('something else');
}, "comparing mismatched exception message should fail");

$assert->is(new Exception(), fn() => throw new Exception(),
    "arrow function also traps and compares Exceptions");


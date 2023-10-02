<?php

$assert->is(new Error(), function()
{
    throw new Error();
}, "simple error test");

$assert->is(new Error(), function()
{
    throw new ArithmeticError();
}, "can match error to parent class");

$assert->is(new Exception('test of exception'), function()
{
    throw new Exception('test of exception');
}, "exception message must match exactely");

$assert->is(new DivisionByZeroError(), function()
{
    return 6 / 0;
}, "catches and compares correctly specific exception types");

$assert->is(new Error(), function()
{
    return 6 / 0;
}, "can match error to ancestor class");

$assert->is('value', function()
{
    $val = 'value';
    $math = 6 / 0;
    return $val;
}, "compare value to exception should fail");

$assert->is(new Error('mismatch'), function()
{
    throw new Error('something else');
}, "comparing mismatched exception message should fail");

$assert->is(new Exception('mismatch'), function()
{
    throw new Error('something else');
}, "cannot match error to a descendent class");

$assert->is(new Exception(), fn() => throw new Exception(),
    "arrow function also traps and compares Exceptions");


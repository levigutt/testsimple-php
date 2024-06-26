# SIMPLE TESTING FRAMEWORK FOR PHP

inspired by [Test::Simple](https://metacpan.org/pod/Test::Simple) for perl,
but not intended to offer identical functionality.

## BACKGROUND

I made this because [PHPUnit](https://phpunit.de/) often feels like overkill
for smaller projects, and like a poor fit for projects not written in OO-style.

I considered [Peridot/Leo](https://github.com/peridot-php/leo) but could not
get it to work on php8 (it also has not been updated in a while).

## SYNOPSIS

writing a test:

example/broken.t:
```php
use functions TestSimple\{ok, is, done_testing};    # import test functions

ok(get_data());                                     # description is optional

is(2, 1+1, "basic math works");                     # is(expected, actual)

ok(function()                                       # trap errors with functions
{
    $c = new Thing();
    return $c->run();
}, "thing can run");

done_testing();
```

running a test:

```sh
$ php example/broken.t
ok 1
ok 2 - basic math works
not ok 3 - thing can run
#	Failed test 'thing can run'
#	at example/broken.phpt:11
1..3
Looks like you failed 1 out of 3 tests
```

we have a failure; `new Thing()` requires a parameter. if this is intended,
we should have a test for it:

example/fixed.t:
```php
use functions TestSimple\{ok, is, done_testing};

ok(true);

is(2, 1+1, "basic math works");

is(new ArgumentCountError(), function()
{
    $c = new Thing();
    return $c->run();
}, "thing cannot run without speed");

ok(function()
{
    $c = new Thing(5);
    return $c->run();
}, "thing can run");

done_testing();
```

and we run it:

```sh
$ php example/fixed.t
ok 1
ok 2 - basic math works
ok 3 - thing cannot run without speed
ok 4 - thing can run
1..4
```

all good :)

## EXIT CODES

if all tests pass, testsimple will exit with zero - indicating no error.
if anything failed, it will exit with how many failed. if the tests were run
incorrectly, it will exit with 255.

```
0           all tests passed
1..254      how many tests failed
255         something went wrong
```

if more than 254 tests fail, it will be reported as 254.

## TESTING EXCEPTIONS

if you pass a throwable as the expected value to `is`, it will compare type,
and message (if defined). it will accept any ancestor class or implemented
interface as a successful match

```php
is(new Exception('Invalid input'), function()
{
    $r = new Request('garbage');
    $r->run();
}, "Request throws exception on invalid input");
```

## CAVEATS

when specifying the number of tests, the actual number of tests reported will
be one higher since this literally adds a test at the end to validate the
number of tests. however, you do not have to take this into consideration when
setting the number of tests.


## OO INTERFACE

```php
$assert = new TestSimple\Assert(plan: 5);
$assert->ok(1,          "1 is truthy");
$assert->is(5, 2+3,     "math works");
$assert->done();                        # ->done_testing() also works
```


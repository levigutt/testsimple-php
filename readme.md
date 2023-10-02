# simple testing framework for php

inspired by [Test::Simple](https://metacpan.org/pod/Test::Simple) for perl,
but not intended to offer identical functionality.

## background

I made this because [PHPUnit](https://phpunit.de/) often feels like overkill
for smaller projects.

I considered [Peridot/Leo](https://github.com/peridot-php/leo) but could not
get it to work on php8 (it also has not been updated in a while).

## getting started

you can make a simple standalone test script, see `example/standalone.php`:

```php
require_once("testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(get_data());                 # description is optional

$assert->is(3, 1+1, "basic math works"); # is(expected, actual)

$assert->ok(function()                   # pass function to trap errors
{
    $c = new Thing();
    return $c->run();
}, "thing can run");

$assert->done();
```

run it with `php example/standalone.php`:

```sh
.FF
Test #2 failed
    /home/user/dev/testsimple/example/standalone.php:7
    basic math works
    expected: 3
    got:      2
Test #3 failed
    /home/user/dev/testsimple/example/standalone.php:9
    thing can run
FAIL (3 assertions, 2 failures)
```

as we can see, two of the tests failed. in the first case it was the test
itself that was wrong, in the other the API for `Thing` seems to require a
parameter to the contructor. if this is intended, we should have a test for
each case

```php
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
```

we run it again:

```sh
$ php example/standalone.php
....
OK (4 assertions)
```

## exit codes

if all tests pass, testsimple will exit with zero - indicating no error.
if anything failed, it will exit with how many failed. if the tests were run
incorrectly, it will exit with 255.

```
0           all tests passed
1..254      how many tests failed
255         something went wrong
```

if more than 254 tests fail, it will be reported as 254.


## other features

**test plan**

you can specify the number of tests to be run upfront as an extra precaution.

```php
$assert = new Assert(5); # will fail unless exactly 5 tests are run
```

when using `prove.php`, the test object is constructed without a test plan, but
you can add one later:

```php
$assert->plan = 5;
```

you can also add or subtract from the plan:

```php
if (PHP_OS_FAMILY === "Windows") {
    $assert->plan+= 1;
    $assert->ok(CheckWindowsThing(), "Check the Windows thing");
}
```

**stop on failure**

you can instruct testsimple to stop the test suit if it encounters a failure.

```php
$assert->stop_on_failure = true;
$assert->ok(false);
$assert->ok(true, "this will not run")
```

if you only need to stop on failure for a specific test, you can instead rely
on the fact that `ok` and `is` returns true on success and false on failure:

```php
$db = new mysqli($srv, $usr, $pwd);
$assert->is(false, $db->connect_error, "can connect to db")
    or $assert->done();
```

## exception handling

if you pass a throwable as the expected value to `is`, it will compare type,
and message (if defined). it will accept any ancestor class or implemented
interface as a successful match

```php
$assert->is(new Exception('Invalid input'), function()
{
    $r = new Request('garbage');
    $r->run();
}, "Request throws exception on invalid input");
```

## caveats

when specifying the number of tests, the actual number of tests reported will
be one higher since this literally adds a test at the end to validate the
number of tests. however, you do not have to take this into consideration when
setting the number of tests.



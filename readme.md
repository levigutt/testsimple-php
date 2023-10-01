# simple testing framework for php

inspired by  [Test::Simple](https://metacpan.org/pod/Test::Simple) for perl,
but not intended to offer identical functionality.

## background

I made this because [PHPUnit](https://phpunit.de/) often feels like overkill
for smaller projects.

I considered [Peridot/Leo](https://github.com/peridot-php/leo) but could not
get it to work on php8 (it also has not been updated in a while).

## getting started

you can make a simple standalone test script, like so:

```php
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
```

and then run it like this:
```
$ php test.php
F..
Test #1 failed
    /home/user/development/project/test.php:5

FAIL (3 assertions, 1 failures
```

the first test failed, showing no description in the output.

if we fix the issues and run again:

```php
<?php
require_once("testsimple.php");
$assert = new TestSimple\Assert();

$assert->ok(true);  # description is optional
$assert->is(2, 1+1, "basic math works");
$assert->ok(function()
{
    $c = count(get_stuff());
    return do_stuff($c);
}, "do_stuff should return true");

$assert->done();
```

```sh
$ php test.php
....
OK (3 assertions)
```

now we see that all tests passed.

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

## larger test suites

for larger test suites you should organise tests in separate files. the files
are loaded and run in order, by file name. start the filename with digits to
control the order of execution.

each test file should contain only the tests:

`t/01-basic.php`:
```php
$assert->is(2, 1+1, "1 plus 1 equals 2");
```

`t/02-form.php`:
```php
$form = new Form(42);
$assert->ok($form->ready(),  "form is ready for use");
$assert->ok($form->submit(), "submit returns true");
```

to run the tests

```sh
./prove.php [dir]
```

`prove.php` will load the framework and set up the test object in variable
`$assert`.

it loads tests from the directory `tests/` unless you specify another
directory.

`prove.php` will capture any exceptions thrown, so that an error in one file
doesn't stop execution of the remaining files. exceptions are reported as
failures in the output.

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

you can also add and subtract from the plan:

```php
if (PHP_OS_FAMILY === "Windows") {
    $assert->plan+= 1;
    $assert->ok( WinSpecificTest() == 1, "Check the Windows thing");
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
on the fact that tests return the original expression:

```php
$assert->ok( ENV == 'development', "only run tests on dev-environment")
    or $assert->done();

$db = new mysqli($srv, $usr, $pwd);
$assert->ok( false == $db->connect_error, "require db connection to proceed")
    or $assert->done();
```

**not ok**

you can use `not_ok($expr)` instead of negating the expression `ok(!$expr)`.

## caveats

you have to do exception handling yourself when using a standalone test script.

when specifying the number of tests, the actual number of tests reported will
be one higher since this literally adds a test at the end to validate the
number of tests. however, you do not have to take this into consideration when
setting the number of tests.



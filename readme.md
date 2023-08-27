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
require_once("testsimple.php");
$test = new TestSimple\Tester();

$test->ok(false);
$test->ok(1 + 1 == 2,     "1 plus 1 equals 2"); # optional description
$test->insist(2 + 2 == 5, "2 plus 2 equals 5"); # stop execution on failure
$test->ok(42 % 5);
$test->done();
```

and then run it like this:
```
$ php test.php
F.F
Test #1 failed

Test #3 failed
        2 plus 2 equals 5
FAIL (3 assertions, 2 failures)
```

the first test failed, showing no description in the output.

the third one failed too, but since it was a `insist` it stopped any further
tests from being run.

if we fix the issues and run again:

```php
require_once("testsimple.php");
$test = new TestSimple\Tester();

$test->ok(true);
$test->ok(1 + 1 == 2,     "1 plus 1 equals 2"); # optional description
$test->insist(2 + 2 == 4, "2 plus 2 equals 4"); # stop execution on failure
$test->ok(42 % 5);
$test->done();
```

```sh
$ php test.php
....
OK (4 assertions)
```

now we see that all tests passed.

## exit codes

if all tests pass, testsimple will exit with zero - indicating no error. if anything failed, it will exit with how many failed. if the tests were run incorrectly, it will exit with 255.

```
0           all tests passed
1..254      how many tests failed
255         something went wrong
```

if more than 254 tests fail, it will be reported as 254.

## larger test suites

for larger test suites you should organise tests in separate files.

each test file should contain only the tests:

`t/basic.php`:
```php
$test->ok(1 + 1 == 2, "1 plus 1 equals 2");
```

`t/form.php`:
```php
$form = new Form(42);
$test->ok($form->ready(),  "form is ready for use");
$test->ok($form->submit(), "submit returns true");
```

to run the tests

```sh
./prove.php [dir]
```

it loads from `tests/` unless you specify another directory.

when using `prove.php` the test object is always named `$test`.

`prove.php` will capture any exceptions thrown, so that an error in one file
doesn't stop execution of the remaining files. exceptions are reported as
failures in the output.

## other features

**test plan**

you can specify the number of tests to be run upfront as an extra precaution.

```php
$tests = new Tester(5); # will fail unless exactly 5 tests are run
```

when using `prove.php`, the test object is constructed without a test plan, but
you can add one later:

```php
$tests->plan_count = 5;
```

you can also add and subtract from the plan:

```php
if (PHP_OS_FAMILY === "Windows") {
    $test->plan_count+= 1;
    $test->ok( WinSpecificTest() == 1, "Check the Windows thing");
}
```

**stop on failure**

you can instruct testsimple to stop the test suit if it encounters a failure.

```php
$test->stop_on_failure = true;
$test->ok(false);
$test->ok(true, "this will not run")
```

if you only need to stop on failure for a specific test, you can instead use
`insist`:

```php
$test->insist( ENV == 'development', "only run tests on dev-environment");

$db = new mysqli($srv, $usr, $pwd);
$test->insist( false == $db->connect_error, "require db connection to proceed");
```

**not ok**

you can use `not_ok($expr)` instead of negating the expression `ok(!$expr)`.

## caveats

you have to do exception handling yourself when using a standalone test script.

when specifying the number of tests, the actual number of tests reported will be one higher since this literally adds a test at the end to validate the number of tests. however, you do not have to take this into consideration when setting the number of tests.



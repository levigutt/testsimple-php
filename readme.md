# simple testing framework for php

inspired by [https://metacpan.org/pod/Test::Simple](Test::Simple) for perl, but not intended to offer identical functionality.

## background

I made this because [https://phpunit.de/](PHPUnit) often feels like overkill for smaller projects.

I considered [https://github.com/peridot-php/leo](Peridot/Leo) but could not get it to work on php8 (it also has not been updated in a while).

## getting started

you can make a simple standalone test script, like so:

```php
require_once("testsimple.php");
$test = new TestSimple\Tester();

$test->ok(true);
$test->ok(1 + 1 == 2, "1 plus 1 equals 2"); # optional description
$test->insist(2 + 2 == 5, "2 plus 2 equals 5"); # stop execution on failure
$test->done();
```

and then run it like this:
```
$ php test.php
```

## larger test suites

for larger test suites you organise tests in separate files in a directory and run them all at once.

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

`prove.php` will run each file in isolation, so that an uncaught exception in one file doesn't stop execution of the remaining files.

exceptions are reported as failures, but not counted as assertions.

## other features

**test plan**

you can specify the number of tests to be run upfront as an extra precaution.

```php
$tests = new Tester(5); # will fail unless exactly 5 tests are run
```

when using `prove.php`, the test object is constructed withtout a test plan, but you can add one later:
```php
$tests->plan_count = 5;
```

you can also add and subtract from the plan:

```php
if (PHP_OS_FAMILY === "Windows") {
    $test->plan_test+= 1;
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

if you only need to stop on failure for a specific test, you can instead use `insist`:

```php
$test->insist( ENV == 'development', "only run tests on dev-environment");

$db = new mysqli($srv, $usr, $pwd);
$test->insist( false == $db->connect_error, "require db connection to proceed");
```

**not ok**

you can use `not_ok($expr)` instead of negating the expression `ok(!$expr)`.


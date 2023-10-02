<?php

namespace TestSimple;

class Assert {
    private int    $test_count      = 0;
    private int    $fail_count      = 0;
    private array  $errors          = [];
    private string $caller          = '';
    private bool   $is_done         = false;
    public  bool   $stop_on_failure = false;
    public int     $file_count      = 0;

    public function __construct(public int $plan = 0){}

    public function __destruct()
    {
        if( !$this->is_done )
        {
            $this->stop_on_failure = false;
            $this->fail("->done() was not called");
            $this->print_result();
            exit(255);
        }
    }

    private function test($expect, $actual) : bool
    {
        $test = ($expect == $actual);
        if( is_callable($actual) )
        {
            try {
                $actual = $actual();
            } catch(\Throwable $th)
            {
                $test = false;
                if( $expect instanceof \Throwable )
                {
                    if( get_class($expect) == get_class($th) )
                        $test = strlen($expect->getMessage())
                              ? ($expect->getMessage() == $th->getMessage())
                              : true;
                    $expect = sprintf("%s('%s')", get_class($expect), $expect->getMessage());
                    $actual = sprintf("%s('%s')", get_class($th), $th->getMessage());
                }
            }
        }
        $this->test_count++;
        return $test;
    }

    # add and validate a new test
    # $test->ok( 1 + 1 == 2, "1 plus 1 equals 2" );
    public function ok(mixed $expression, string $description = '', bool $negate = false) : bool
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $this->caller = sprintf("%s:%s", $caller['file'], $caller['line']);
        $expect = true;
        if( $negate )
            $expect = false;
        $result = $this->test($expect, $expression);
        if( !$result )
            $this->fail($description);
        else
            echo ".";
        flush();
        return $result;
    }

    # add and validate a new test
    # $test->is( 5, "5", "5 == '5'" );
    public function is($expect, $actual, $msg = '') : bool
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $this->caller = sprintf("%s:%s", $caller['file'], $caller['line']);
        if( is_callable($actual) )
        {
            try {
                $actual = $actual();
                $test = ($expect === $actual);
            } catch(\Throwable $th)
            {
                $test = false;
                if( $expect instanceof \Throwable )
                {
                    $error_types = array_merge(class_parents($th), class_implements($th));
                    if( get_class($expect) == get_class($th)
                     || in_array(get_class($expect), $error_types) )
                        $test = strlen($expect->getMessage())
                              ? ($expect->getMessage() == $th->getMessage())
                              : true;
                    $expect = sprintf("%s('%s')", get_class($expect), $expect->getMessage());
                }
                $actual = sprintf("%s('%s')", get_class($th), $th->getMessage());
            }
        }
        else
            $test = ($expect === $actual);
        $this->test_count++;
        if( !$test )
            $this->fail( sprintf(   "%s\n\texpected: %s\n\tgot:      %s"
                                ,   $msg
                                ,   $expect instanceof Stringable ? "<$expect>" : print_r($expect, true)
                                ,   $actual instanceof Stringable ? "<$actual>" : print_r($actual, true)
                                ) );
        else
            echo ".";
        flush();
        return !!$test;
    }

    # fail the current test
    public function fail(string $msg, string $chr = 'F')
    {
        echo $chr;
        $this->fail_count++;
        $this->errors[] = sprintf("\nTest #%d failed\n\t%s",
                             $this->test_count,
                             ($this->caller ? $this->caller . "\n\t":'').$msg);
        if( $this->stop_on_failure )
            $this->done();
    }

    public function done()
    {
        $this->is_done = true;
        if( $this->plan )
        {
            $this->ok( $this->plan == $this->test_count,
                sprintf("wrong number of tests\n\texpected %d, but ran %d\n",
                        $this->plan,
                        $this->test_count));
        }
        $this->print_result();
        exit($this->fail_count > 254 ? 254 : $this->fail_count);
    }

    private function print_result()
    {
        foreach( $this->errors as $error )
            print $error;

        if( $this->fail_count == 0 )
            printf("\n\033[92mOK (%d assertions)", $this->test_count);
        else
            printf("\n\033[91mFAIL (%d assertions, %d failures)",
                $this->test_count, $this->fail_count);
        print "\033[0m\n";
    }

}

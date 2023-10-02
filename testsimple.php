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

    # add and validate a new test
    # $test->ok( 1 + 1 == 2, "1 plus 1 equals 2" );
    public function ok(mixed $expr, string $msg = '', bool $negate = false)
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $this->caller = sprintf("%s:%s", $caller['file'], $caller['line']);
        if( is_callable($expr) )
        {
            try {
                $expr = $expr();
            } catch(\Throwable $th)
            {
                $expr = false;
            }
        }
        $this->test_count++;
        if( $negate )
            $expr = !$expr;
        if( !$expr )
            $this->fail($msg);
        else
            echo ".";
        flush();
        return !!$expr;
    }

    # add and validate a new test
    # $test->is( 5, "5", "5 == '5'" );
    public function is($expected, $value, $msg = '')
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $this->caller = sprintf("%s:%s", $caller['file'], $caller['line']);
        if( is_callable($value) )
        {
            try {
                $value = $value();
                $expr = ($expected === $value);
            } catch(\Throwable $th)
            {
                $expr = false;
                if( $expected instanceof \Throwable )
                {
                    if( get_class($expected) == get_class($th) )
                    {
                        $expr = strlen($expected->getMessage()) ? ($expected->getMessage() == $th->getMessage())
                                                                : true;
                    }
                    $expected = sprintf("%s('%s')", get_class($expected), $expected->getMessage());
                    $value = sprintf("%s('%s')", get_class($th), $th->getMessage());
                }
            }
        }
        else
            $expr = ($expected === $value);
        $this->test_count++;
        if( !$expr )
            $this->fail( sprintf(   "%s\n\texpected: %s\n\tgot:      %s"
                                ,   $msg
                                ,   $expected instanceof Stringable ? "<$expected>" : print_r($expected, true)
                                ,   $value    instanceof Stringable ? "<$value>"    : print_r($value, true)
                                ) );
        else
            echo ".";
        flush();
        return !!$expr;
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

    public function not_ok($expr, $msg = '')
    {
        $this->ok( $expr, $msg, true );
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

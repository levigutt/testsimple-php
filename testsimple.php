<?php

namespace TestSimple;

class Assert {
    private int    $test_count      = 0;
    private int    $fail_count      = 0;
    private array  $errors          = [];
    private string $caller          = '';
    private bool   $is_done         = false;
    public  bool   $stop_on_failure = false;

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
    public function ok($expr, $msg = '', $negate = false)
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
        if( !$expr && !$negate )
        {
            $this->fail($msg);
        }
        else
            echo ".";
        flush();
    }

    # fail the current test
    public function fail(string $msg, string $chr = 'F')
    {
        echo $chr;
        $this->fail_count++;
        $this->errors[] = sprintf("\nTest #%d failed\n\t%s\n\t%s",
                             $this->test_count,
                             $this->caller,
                             $msg);
        if( $this->stop_on_failure )
            $this->done();
    }

    public function not_ok($expr, $msg = '')
    {
        $this->ok( $expr, $msg, true );
    }

    public function insist($expr, $msg = '')
    {
        $sof = $this->stop_on_failure;
        $this->stop_on_failure = true;
        $this->ok($expr, $msg);
        $this->stop_on_failure = $sof;
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

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
    private bool   $plan_printed    = false;

    public function __construct(public int $plan = 0){
        if( $this->plan )
        {
            printf("1..%d\n", $this->plan);
            $this->plan_printed = true;
        }
    }

    public function __destruct()
    {
        if( !$this->is_done )
        {
            if( $this->plan > 0 )
            {
                if( $this->fail_count == 0 && $this->plan == $this->test_count )
                    exit(0);
                if( $this->plan != $this->test_count )
                    $this->diag(sprintf(  "wrong number of tests\n\texpected %d, but ran %d\n"
                                       ,  $this->plan
                                       ,  $this->test_count
                                       )
                               );
                if( $this->fail_count )
                    $this->done();
            }else
            {
                $this->stop_on_failure = false;
                $this->fail("->done() was not called");
                $this->print_result();
            }
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
            $this->pass($description);
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
                    $error_types = array_merge(  class_parents($th)
                                              ,  class_implements($th)
                                              );
                    if( get_class($expect) == get_class($th)
                     || in_array(get_class($expect), $error_types) )
                        $test = strlen($expect->getMessage())
                              ? ($expect->getMessage() == $th->getMessage())
                              : true;
                    $expect = sprintf(  "%s('%s')"
                                     ,  get_class($expect)
                                     ,  $expect->getMessage()
                                     );
                }
                $actual = sprintf(  "%s('%s')"
                                 ,  get_class($th)
                                 ,  $th->getMessage()
                                 );
            }
        }
        else
            $test = ($expect === $actual);
        $this->test_count++;
        if( !$test )
            $this->fail( sprintf(   "%s\n\texpected: %s\n\tgot:      %s"
                                ,   $msg
                                ,   gettype($expect) .' '. (can_print($expect) ? "<$expect>" : print_r($expect, true))
                                ,   gettype($actual) .' '. (can_print($actual) ? "<$actual>" : print_r($actual, true))
                                ) );
        else
            $this->pass($msg);
        flush();
        return !!$test;
    }

    # fail the current test
    public function fail(string $description)
    {
        printf(  "%s %d"
              ,  "not ok"
              ,  $this->test_count
              );
        $this->fail_count++;

        $this->diag(sprintf(   "\nTest #%d failed\n\t%s"
                           ,   $this->test_count
                           ,   ($this->caller ? $this->caller . "\n\t":'').$description
                           ));
        if( $this->stop_on_failure )
            $this->done();
    }

    public function pass(string $description = '')
    {
        printf(  "%s %d%s\n"
              ,  "ok"
              ,  $this->test_count
              ,  ($description ? " - $description" : '')
              );
    }

    public function diag(string $msg)
    {
        $error = join("\n#", explode("\n", $msg));
        if( "\n" != substr(strlen($error)-1, 1) )
            $error.= "\n";
        print $error;
    }

    public function done()
    {
        $this->is_done = true;
        printf("1..%d\n", $this->test_count);
        $this->print_result();
        if( 0 == $this->fail_count && !$this->check_plan() )
            exit(255);
        exit($this->fail_count > 254 ? 254 : $this->fail_count);
    }

    private function check_plan()
    {
        return !($this->plan > 0 && $this->plan != $this->test_count);
    }

    private function print_result()
    {
        foreach( $this->errors as $error )
            print $error;

        if( $this->fail_count > 0 )
            printf(  "Looks like you failed %d out of %d tests\n"
                  ,  $this->fail_count
                  ,  $this->test_count
                  );
    }

}


function can_print($var) : bool
{
    return ($var instanceOf Stringable) || in_array(gettype($var), ['string', 'int']);
}



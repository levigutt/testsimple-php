<?php

namespace TestSimple;

class Assert {
    private int    $test_count      = 0;
    private int    $fail_count      = 0;
    private string $caller          = '';
    private bool   $is_done         = false;
    public  bool   $stop_on_failure = false;
    public int     $file_count      = 0;

    public function __construct(public int $plan = 0){
        if( $this->plan )
            printf("1..%d\n", $this->plan);
    }

    public function __destruct()
    {
        if( $this->is_done )
            return;
        if( $this->plan > 0 )
        {
            if( $this->fail_count == 0 )
                exit(0);
            if( $this->fail_count )
                $this->done();
        }
        $this->print_result();
        exit(255);
    }

    private function test($expect, $actual) : bool
    {
        if( !is_callable($actual) )
            return ($expect == $actual);

        try
        {
            return ($expect == $actual());
        } catch(\Throwable $th)
        {
            return false;
        }
    }

    public function ok(mixed $expression, string $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $caller = array_shift($trace);
        $file = ltrim(str_replace(getcwd(), '', $caller['file']), '/');
        $this->caller = sprintf("%s:%s", $file, $caller['line']);
        $result = $this->test(true, $expression);
        if( !$result )
            $this->fail('', $description);
        else
            $this->pass($description);
        return $result;
    }

    public function is($expect, $actual, $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $caller = array_shift($trace);
        $file = ltrim(str_replace(getcwd(), '', $caller['file']), '/');
        $this->caller = sprintf("%s:%s", $file, $caller['line']);
        if( is_callable($actual) )
        {
            try
            {
                $actual = $actual();
                $test = ($expect === $actual);
            } catch(\Throwable $th)
            {
                $test = false;
                if( $expect instanceof \Throwable )
                {
                    $valid_error_classes = array_merge(  class_parents($th)
                                                      ,  class_implements($th)
                                                      ,  [get_class($th)]
                                                      );
                    if( in_array(get_class($expect), $valid_error_classes) )
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
        if( !$test )
            $this->fail(  sprintf(   "\n\texpected: %s\n\t     got: %s"
                                 ,   (can_print($expect) ? "<$expect>"
                                                         : print_r($expect, true)
                                     )
                                 ,   (can_print($actual) ? "<$actual>"
                                                         : print_r($actual, true)
                                     )
                                 )
                       ,  $description
                       );
        else
            $this->pass($description);
        flush();
        return !!$test;
    }

    private function fail(string $failure, string $description = null)
    {
        printf(  "%s %d%s"
              ,  "not ok"
              ,  $this->test_count
              ,  $description ? " - $description" : ''
              );
        $this->fail_count++;

        $this->diag(sprintf(   "\n\tFailed test %s%s%s"
                           ,   $description   ? "'$description'"      : ''
                           ,   $failure
                           ,   $this->caller ? "\n\tin $this->caller" : ''
                           )
                   );
        if( $this->stop_on_failure )
            $this->done();
    }

    private function pass(string $description = '')
    {
        printf(  "%s %d%s\n"
              ,  "ok"
              ,  $this->test_count
              ,  ($description ? " - $description" : '')
              );
    }

    private function diag(string $msg)
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
        if( $this->fail_count > 0 )
            printf(  "Looks like you failed %d out of %d tests\n"
                  ,  $this->fail_count
                  ,  $this->test_count
                  );
    }

}


function can_print($var) : bool
{
    return ($var instanceOf Stringable)
        || in_array(gettype($var), ['string', 'int', 'float', 'double']);
}



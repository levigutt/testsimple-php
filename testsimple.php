<?php

namespace TestSimple;

class Assert {
    private int    $test_count      = 0;
    private int    $fail_count      = 0;
    private bool   $is_done         = false;
    public  bool   $stop_on_failure = false;

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
                $this->done(false);
        }
        $this->print_result();
        exit(255);
    }

    private function format_calling_location($trace) : string
    {
        $caller = array_shift($trace);
        $file = ltrim(str_replace(getcwd(), '', $caller['file']), '/');
        return sprintf("%s:%s", $file, $caller['line']);
    }

    public function ok(mixed $expression, string $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $calling_location = $this->format_calling_location($trace);

        $result = !!$expression;
        if( is_callable($expression) )
        {
            try
            {
                $result = !!$expression();
            } catch(\Throwable $th)
            {
                $result = false;
            }
        }

        if( !$result )
            $this->fail('', $description, $calling_location);
        else
            $this->pass($description);
        return $result;
    }

    public function is($expect, $actual, $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $calling_location = $this->format_calling_location($trace);
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
                    $expect_dump = sprintf(  '%s("%s")'
                                          ,  get_class($expect)
                                          ,  $expect->getMessage()
                                          );
                }
                $actual_dump = sprintf(  '%s("%s")'
                                      ,  get_class($th)
                                      ,  $th->getMessage()
                                      );
            }
        }
        else
        {
            $expect_dump = var_dump_str($expect);
            $actual_dump = var_dump_str($actual);
            $test = ($expect === $actual);
        }
        if( !$test )
            $this->fail(  sprintf(   "\n\texpected: %s\n\t     got: %s"
                                 ,   $expect_dump
                                 ,   $actual_dump
                                 )
                       ,  $description
                       ,  $calling_location
                       );
        else
            $this->pass($description);
        flush();
        return !!$test;
    }

    private function fail(  string $failure
                         ,  string $description = null
                         ,  string $calling_location = null
                         )
    {
        printf(  "%s %d%s"
              ,  "not ok"
              ,  $this->test_count
              ,  $description ? " - $description" : ''
              );
        $this->fail_count++;

        $this->diag(sprintf(   "\n\tFailed test %s%s%s"
                           ,   $description   ? "'$description'\n\t"    : ''
                           ,   $calling_location ? "at $calling_location" : ''
                           ,   $failure
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

    public function done(bool $show_plan = true)
    {
        $this->is_done = true;
        if( $show_plan )
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

function var_dump_str(...$var) : string
{
    ob_start();
    var_dump(...$var);
    $str = ob_get_clean();
    if( "\n" == substr($str, strlen($str)-1, 1) )
        $str = substr($str, 0, strlen($str)-1);
    return $str;
}

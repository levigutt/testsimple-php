<?php

namespace TestSimple;

class Assert {
    private int  $test_count      = 0;
    private int  $fail_count      = 0;
    private bool $is_done         = false;

    public function __construct(  public int $plan = 0
                               ,  public bool $stop_on_failure = false
                               )
    {
        if( $this->plan )
            printf("1..%d\n", $this->plan);
    }

    public function __destruct()
    {
        $missing_tests = $this->plan - $this->test_count;
        if( 0 < $missing_tests )
            $this->fail_count+= $missing_tests;
        if( $this->fail_count > 0 )
            printf(  "Looks like you failed %d out of %d tests\n"
                  ,  $this->fail_count
                  ,  $this->test_count
                  );
        $exit_code = $this->fail_count > 254 ? 254 : $this->fail_count;
        if( 0 == $this->test_count || (!$this->is_done && $this->plan == 0) )
            $exit_code = 255;
        exit($exit_code);
    }

    public function done()
    {
        $this->is_done = true;
        printf("1..%d\n", $this->test_count);
        exit; // destructor prints result and sets exit code
    }

    public function ok(mixed $expression, string $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $calling_location = $this->format_calling_location($trace);
        $error = '';
        try
        {
            if( is_callable($expression) ? $expression() : $expression )
                return $this->pass($description);
        } catch(\Throwable $err)
        {
            $error = $err->getMessage();
        }
        return $this->fail($calling_location, $description, '');
    }

    public function is($expect, $actual, $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $calling_location = $this->format_calling_location($trace);
        try
        {
            if( $expect === (is_callable($actual) ? $actual() : $actual) )
                return $this->pass($description);
        } catch(\Throwable $err)
        {
            $actual = $err;
            if( $expect instanceof \Throwable && $this->cmp_error($expect, $actual) )
                return $this->pass($description);
        }
        return $this->fail(  $calling_location
                          ,  $description
                          ,  sprintf(   "\n%8s: %s\n%8s: %s"
                                    ,   'expected'
                                    ,   inspect_var($expect)
                                    ,   'got'
                                    ,   inspect_var($actual)
                                    )
                          );
    }

    private function cmp_error($expect, $actual) : bool
    {
        $valid_classes = array_merge(  class_parents($actual)
                                    ,  class_implements($actual)
                                    ,  [get_class($actual)]
                                    );
        $class_is_valid     = in_array(get_class($expect), $valid_classes);
        $msg_empty_or_match = strlen($expect->getMessage())
                            ? $expect->getMessage() == $actual->getMessage()
                            : true;
        return $class_is_valid && $msg_empty_or_match;
    }

    private function fail(  string $calling_location
                         ,  string $description = null
                         ,  string $failure = null
                         )
    {
        printf(  "%s %d%s"
              ,  "not ok"
              ,  $this->test_count
              ,  $description ? " - $description" : ''
              );
        $this->fail_count++;

        $this->diag(sprintf(   "\nFailed test %s%s%s"
                           ,   $description ? "'$description'\n"   : ''
                           ,   "at $calling_location"
                           ,   $failure ?? ''
                           )
                   );
        if( $this->stop_on_failure )
            $this->done();
        return false;
    }

    private function pass(string $description = '')
    {
        printf(  "%s %d%s\n"
              ,  "ok"
              ,  $this->test_count
              ,  ($description ? " - $description" : '')
              );
        return true;
    }

    private function diag(string $msg)
    {
        $diag = join("\n#\t", explode("\n", $msg));
        if( "\n" != substr(strlen($diag)-1, 1) )
            $diag.= "\n";
        print $diag;
    }

    private function format_calling_location($trace) : string
    {
        $caller = array_shift($trace);
        $file = ltrim(str_replace(getcwd(), '', $caller['file']), '/');
        return sprintf("%s:%s", $file, $caller['line']);
    }
}

function inspect_var($var) : string
{
    if( $var instanceOf \Throwable )
        return sprintf('%s("%s")', get_class($var), $var->getMessage());
    if( 'string' == gettype($var) )
        return sprintf('string(%d) "%s"', strlen($var), $var);
    if( 'array' == gettype($var) )
    {
        $inspection = '[';
        $count = 0;
        foreach($var as $item)
        {
            if( $count )
                $inspection.= ',';
            $inspection.= inspect_var($item);
            $count++;
        }
        $inspection.= ']';
        return $inspection;
    }
    if( 'object' == gettype($var) )
        return sprintf('object(%s)', get_class($var));
    return sprintf('%s(%s)', gettype($var), $var);
}


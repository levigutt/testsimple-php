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
        $exit_code = min(254, $this->fail_count);
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
        $call_location = $this->format_call_location($trace);
        $error = '';
        try
        {
            if( is_callable($expression) ? $expression() : $expression )
                return $this->pass($description);
        } catch(\Throwable $err)
        {
            $error = $err->getMessage();
        }
        return $this->fail($call_location, $description, '');
    }

    public function is($expect, $actual, string $description = '') : bool
    {
        $this->test_count++;
        $trace = debug_backtrace();
        $call_location = $this->format_call_location($trace);
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
        $failure = sprintf(  "\n%8s: %s\n%8s: %s"
                          ,  'expected'
                          ,  $this->inspect_var($expect)
                          ,  'got'
                          ,  $this->inspect_var($actual)
                          );
        return $this->fail($call_location, $description, $failure);
    }

    private function cmp_error(\Throwable $expect, \Throwable $actual) : bool
    {
        $valid_classes = array_merge(  class_parents($actual)
                                    ,  class_implements($actual)
                                    ,  [get_class($actual)]
                                    );
        if( ! in_array(get_class($expect), $valid_classes) )
            return false;
        if( 0 == strlen($expect->getMessage()) )
            return true;
        return $expect->getMessage() == $actual->getMessage();
    }

    private function fail(  string $call_location
                         ,  string $description = null
                         ,  string $failure = null
                         )
    {
        $this->fail_count++;
        $result      = sprintf("not ok %d", $this->test_count);
        $diagnostics = "Failed test ";

        if( $description )
        {
            $result.= " - $description";
            $diagnostics.= "'$description'\n";
        }
        $diagnostics.= "at $call_location";
        if( $failure )
            $diagnostics.= $failure;

        printf("%s\n", $result);
        print $this->diag($diagnostics);

        if( $this->stop_on_failure )
            $this->done();
        return false;
    }

    private function pass(string $description = null)
    {
        $msg = sprintf("ok %d", $this->test_count);
        if( $description )
            $msg.= sprintf(" - %s", $description);
        printf("%s\n", $msg);
        return true;
    }

    private function diag(string $msg)
    {
        $lines       = explode("\n", $msg);
        $formatted   = array_map(fn($x) => sprintf("#\t%s", $x), $lines);
        $diagnostics = join("\n", $formatted);
        return sprintf("%s\n", $diagnostics);
    }

    private function format_call_location(array $trace) : string
    {
        $caller = array_shift($trace);
        $file   = ltrim(str_replace(getcwd(), '', $caller['file']), '/');
        return sprintf("%s:%d", $file, $caller['line']);
    }

    private function inspect_var($var) : string
    {
        if( $var instanceOf \Throwable )
            return sprintf('%s("%s")', get_class($var), $var->getMessage());
        if( 'string' == gettype($var) )
            return sprintf('string(%d) "%s"', strlen($var), $var);
        if( 'array' == gettype($var) )
            return sprintf('[%s]', join(',', array_map(__METHOD__, $var)));
        if( 'object' == gettype($var) )
            return sprintf('object(%s)', get_class($var));
        return sprintf('%s(%s)', gettype($var), $var);
    }
}

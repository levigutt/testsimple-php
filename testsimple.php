<?php

namespace TestSimple;

class Tester {
    private bool $is_done = false;
    private int $test_count = 0;
    private int $fail_count = 0;

    public function __construct(public bool $verbose = false){}

    public function __destruct()
    {
        if( !$this->is_done )
        {
            $this->print_status();
            exit(255);
        }
    }

    public function ok($expr, $msg)
    {
        $this->test_count++;
        if( !$expr )
        {
            $this->fail_count++;
            printf("\nTest #%d failed\n\t%s\n", $this->test_count, $msg);
        }
        else
        {
            if( $this->verbose )
            {
                printf("\nTest #%d succeeded\n\t%s\n", $this->test_count, $msg);
            }
        }
    }

    public function not_ok($expr, $msg)
    {
        return $this->ok(!$expr, $msg);
    }

    public function done()
    {
        $this->is_done = true;
        $this->print_status();
        exit($this->fail_count);
    }

    private function print_status()
    {
        printf("Test suite %s\n", 0 == $this->fail_count ? "succeeded" : "failed");
        printf("\t%d/%d tests succeeded\n",
              $this->test_count - $this->fail_count,
              $this->test_count,
        );
        if( !$this->is_done )
        {
            printf("\tTester::done() was never called\n");
        }
    }

}

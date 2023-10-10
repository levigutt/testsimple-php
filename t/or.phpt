#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 2);

$result = exec("php t/res/or.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("ok 2", $parsed[1]['test'], "passing test returns true");
$assert->is("ok 4", $parsed[3]['test'], "failing test returns false");


function parse_output(array $lines)
{
    $parsed = [];
    foreach($lines as $line)
    {
        if( !strlen($line) ) continue;
        if( in_array(substr($line, 0, 1), ['#', "\t"]) )
        {
            $parsed[count($parsed)-1]['diag'][] = $line;
            continue;
        }

        $frag = explode('-', $line, 2);
        $test = trim($frag[0]);
        $desc = trim($frag[1] ?? '');
        $parsed[] = [  'test' => $test
                    ,  'desc' => $desc
                    ];
    }
    return $parsed;
}

#!/usr/bin/php
<?php

require_once "vendor/autoload.php";
$assert = new TestSimple\Assert(plan: 5);

unset($out);
$result = exec("php t/res/is.php", $out, $retval);
$parsed = parse_output($out);
$assert->is("1..4", $parsed[0]['test']);
$assert->is("ok 1", $parsed[1]['test']);
$assert->is("ok 2", $parsed[2]['test']);
$assert->is("not ok 3", $parsed[3]['test']);
$assert->is("not ok 4", $parsed[4]['test']);


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

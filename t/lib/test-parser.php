<?php


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

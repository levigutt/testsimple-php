<?php

require_once("vendor/autoload.php");
$assert = new TestSimple\Assert(plan: 4);

// should fail
$assert->is([1,2,3], [3,2,1]);
$assert->is([1,2,3], ['a','b']);
$assert->is([[1],2,3], [3,[2],1]);
$assert->is(['name' => 'robert', 'nick' => 'bob'], ['nick' => 'frank', 'name' => 'francis']);

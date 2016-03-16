<?php

// Variables
$arr = range( 1, 30000 );
$test = function(){};
function test(){};


// Test 1: array_map('test', $arr)
$temp = microtime( true );
array_map('test', $arr);
$temp1 = ( microtime( true ) - $temp );
echo 'Test 1: array_map("test", $arr) = '.$temp1.'<br>';

// Test 2: array_map($test, $myArray)
$temp = microtime( true );
array_map( $test, $arr );
$temp1 = ( microtime( true ) - $temp );
echo 'Test 2: array_map($test, $arr) = '.$temp1.'<br>';

// Test 3: array_map(function(){}, $myArray)
$temp = microtime( true );
array_map( function(){}, $arr );
$temp1 = ( microtime( true ) - $temp );
echo 'Test 3: array_map(function{}(), $arr) = '.$temp1.'<br>';
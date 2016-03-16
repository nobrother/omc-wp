<?php

function lap($func) {
  $t0 = microtime(1);
  $numbers = range(0, 5000);
  $ret = $func($numbers);
  $t1 = microtime(1);
  return array($t1 - $t0, $ret);
}

function useForeach($numbers)  {
  $result = array();
  foreach ($numbers as $number) {
      $result[] = $number * 10;
  }
  return $result;
}

function useMapClosure($numbers) {
  return array_map(function($number) {
      return $number * 10;
  }, $numbers);
}

function _tenTimes($number) {
    return $number * 10;
}

function useMapNamed($numbers) {
  return array_map('_tenTimes', $numbers);
}

foreach (array('Foreach', 'MapClosure', 'MapNamed') as $callback) {
  list($delay,) = lap("use$callback");
  echo "$callback: $delay<br>";
}
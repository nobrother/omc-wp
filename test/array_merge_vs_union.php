<?php
 
// Variables
$limit = 5000;
$arr_size = 2;
 
// Build a base array
for ($i=0;$i<$arr_size;$i++) {
 $arr[$i] = 0;
}
 
// Test 1 with array_merge
$tab1 = array();
$j = 0;
$temp = microtime(true);
while ($j<$limit) {
 $tab1 = array_merge($tab1, $arr);
 $j++;
}
$temp1 = (microtime(true) - $temp);
echo "Temp with array_merge : ".$temp1;
 
// Test 2 with + union
$tab2 = array();
$k = 0;
$temp = microtime(true);
while ($k<$limit) {
 $tab2 += $arr;
 $k++;
}
$temp2 = (microtime(true) - $temp);
 
echo "<br> Temp with + union : ".$temp2."<br>";
 
// Compare the time used by the 2 ways
echo sprintf('Union est %5d fois plus rapide que array_merge', ((float)$temp1/(float)$temp2));
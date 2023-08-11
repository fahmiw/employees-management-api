<?php 
function plusMinus($arr) {
    $n = count($arr);
    $positive = $negative = $zero = 0;

    foreach ($arr as $num) {
        if ($num > 0) {
            $positive++;
        } elseif ($num < 0) {
            $negative++;
        } else {
            $zero++;
        }
    }

    $positiveRatio = $positive / $n;
    $negativeRatio = $negative / $n;
    $zeroRatio = $zero / $n;

    return $positiveRatio . "\n" . $negativeRatio . "\n" . $zeroRatio . "\n";
}

$arr = [-4, 3, -9, 0, 4, 1];
$result = plusMinus($arr);
echo $result;

?>
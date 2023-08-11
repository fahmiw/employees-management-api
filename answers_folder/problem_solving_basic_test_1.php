<?php
function miniMaxSum($arr) {
    sort($arr); // Menyusun integer dari terkecil ke terbesar

    $minSum = array_sum(array_slice($arr, 0, 4)); // Menambahkan integer kecuali angka terbesar
    $maxSum = array_sum(array_slice($arr, 1));   // Menambahkan integer kecuali angka terkecil

    return $minSum . " " . $maxSum;
}

$input = [1, 2, 3, 4, 5];
$result = miniMaxSum($input);
echo $result;
?>
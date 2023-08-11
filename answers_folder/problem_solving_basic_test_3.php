<?php 
function timeConversion($s) {
    $timeComponents = explode(":", $s);
    $hour = intval($timeComponents[0]);
    $minute = $timeComponents[1];
    $second = substr($timeComponents[2], 0, 2); // Menghapus AM atau PM

    if (substr($s, -2) === "PM" && $hour != 12) {
        $hour += 12;
    } elseif (substr($s, -2) === "AM" && $hour == 12) {
        $hour = 0;
    }

    return $hour. ":" . $minute .":". $second;
}

$s = "07:05:45PM";
$result = timeConversion($s);
echo $result;
<?php
function columnToNumber($column){
    $length = strlen($column);

    $number = 0;
    for($i = 0; $i < $length; $i++){
        $number = $number * 26 + (ord($column[$i]) - ord('A') + 1);
    }
    return $number;
}

function getCellValue($cell){
    preg_match('/([A-Z]+)([0-9]+)/', strtoupper($cell), $matches);
    $column = $matches[1];
    $row = $matches[2];
    $columnNumber = columnToNumber($column);
    return "{$columnNumber}.{$row}";
}
echo getCellValue("A2") . "\n"; // Wyjście: 1.2
echo getCellValue("B2") . "\n"; // Wyjście: 2.2
echo getCellValue("A500") . "\n"; // Wyjście: 1.500
echo getCellValue("AA10") . "\n"; // Wyjście: 27.10
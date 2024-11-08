<?php
function generateHtmlCalendar($month, $year) {
    $monthName = date("F", mktime(0, 0, 0, $month, 1, $year));
    $daysOfWeek = ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"];

    $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
    $numberOfDays = date('t', strtotime("$year-$month-01"));


    $html = "    <html>
    <head>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th {
                background-color: #f2f2f2;                padding: 10px;
            }
            td {
                padding: 10px;
                text-align: center;
                border: 1px solid #ddd;
            }
            .sunday {                color: red;
            }
        </style><title></title>
    </head>
    <body>
        <h2>$monthName $year</h2>
        <table>
            <tr>";

    foreach ($daysOfWeek as $index => $day) {
        $class = $index == 6 ? 'class="sunday"' : '';
        $html .= "<th $class>$day</th>";
    }

    $html .= "</tr><tr>";

    $currentDay = 1;
    for ($i = 1; $i < $firstDayOfMonth; $i++) {
        $html .= "<td></td>";
    }

    for ($day = 1; $day <= $numberOfDays; $day++) {
        $class = (($currentDay + $firstDayOfMonth - 2) % 7 == 6) ? 'class="sunday"' : '';
        $html .= "<td $class>$day</td>";

        if (($currentDay + $firstDayOfMonth - 2) % 7 == 6) {
            $html .= "</tr><tr>";
        }

        $currentDay++;
    }

    while (($currentDay + $firstDayOfMonth - 2) % 7 != 0) {
        $html .= "<td></td>";
        $currentDay++;
    }

    $html .= "</tr></table></body></html>";

    return $html;
}

// Przykładowe wywołanie dla grudnia 2024
echo generateHtmlCalendar(11, 2024);


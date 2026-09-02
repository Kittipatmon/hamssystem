<?php

namespace App\Helpers;

use Carbon\Carbon;

class ThaiDate
{
    public static function format($date, $format = 'datetime')
    {
        if (!$date) return '-';

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
            7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        $year = $date->year + 543;
        $month = $months[$date->month];
        $day = $date->day;
        
        if ($format == 'date') {
            return "$day $month $year";
        }

        $time = $date->format('H:i');
        return "$day $month $year $time";
    }
}

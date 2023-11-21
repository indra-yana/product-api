<?php

namespace App\Helpers;
use Illuminate\Support\Str;

class Utils {

    public static function splitCamelCase($str) {
        $regex = '/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/x';

        return implode(' ', preg_split($regex, str_replace('_', ' ', $str)));
    }

    public static function randomString($init='', $length, $type='text_number') {   
        if ($type == 'text') {
            $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else if ($type == 'number') {
            $str = '0123456789';
        } else if ($type == 'text_number') {
            $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }

        $suffle = str_shuffle($str);
        
        return $init .substr($suffle, 1, $length);
    }

    public static function transformDate($value, $format='Y-m-d') {
        $month = [
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'Mei' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Agu' => 'Aug',
            'Sep' => 'Sep',
            'Okt' => 'Oct',
            'Nov' => 'Nov',
            'Des' => 'Dec',
            'May' => 'May',
            'Aug' => 'Aug',
            'Oct' => 'Oct',
            'Dec' => 'Dec',
        ];

        $dateArray = explode('-', $value);

        if (!isset($dateArray[1]) ) {
            return $value;
        }

        if (array_key_exists($dateArray[1], $month)) {
            $dateStr = str_replace($dateArray[1],  $month[$dateArray[1]], implode('-', $dateArray));

            return date_format(date_create($dateStr), $format);
        }

        return $value;
    }

    public static function getLocalDayName($dayOfWeek) {
        $dayNames = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ];

        return $dayNames[$dayOfWeek];
    }

    public static function getDatePeriod($start, $end) {
        return date_diff(date_create($start), date_create($end));
    }

    public static function createInvID($invID, $threshold = 6) {
        $invIDCount = strlen((string) $invID);
        if ($invIDCount >= $threshold) {
            $threshold = $invIDCount + 1;
        }

        return str_pad($invID, $threshold, '0', STR_PAD_LEFT);
    }

    public static function browserInfo() {
        $browser = \Browser::detect()->toArray();

        if ( !empty($browser) ) {
            return [
                'browser_name' => $browser['browserName'],
                'browser_family' => $browser['browserFamily'],
                'browser_version' => $browser['browserVersion'],
                'platform_name' => $browser['platformName'],
            ];
        }

        return [];
    }

    /*
     * String manipulation to extract the needed sub parts from a string
     */

    // str_after ('@', 'biohazard@online.ge');
    // returns 'online.ge'
    public static function str_after($char, $inthat) {
        if (!is_bool(strpos($inthat, $char)))
        return substr($inthat, strpos($inthat, $char)+strlen($char));
    }

    // str_after_last ('[', 'sin[90]*cos[180]');
    // returns '180]'
    public static function str_after_last($char, $inthat) {
        if (!is_bool(self::strrevpos($inthat, $char)))
        return substr($inthat, self::strrevpos($inthat, $char)+strlen($char));
    }

    // str_before ('@', 'biohazard@online.ge');
    // returns 'biohazard'
    public static function str_before($char, $inthat) {
        return substr($inthat, 0, strpos($inthat, $char));
    }

    // str_before_last ('[', 'sin[90]*cos[180]');
    // returns 'sin[90]*cos['
    public static function str_before_last($char, $inthat) {
        return substr($inthat, 0, self::strrevpos($inthat, $char));
    }

    // str_between ('@', '.', 'biohazard@online.ge');
    // returns 'online'
    public static function str_between($char, $char2, $inthat) {
        return self::before($char2, self::after($char, $inthat));
    }

    // str_between_last ('[', ']', 'sin[90]*cos[180]');
    // returns '180'
    public static function str_between_last($char, $char2, $inthat) {
        return self::after_last($char, self::before_last($char2, $inthat));
    }

    // use strrevpos function in case your php version does not include it
    public static function strrevpos($instr, $needle) {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }
}

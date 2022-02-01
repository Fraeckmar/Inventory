<?php

class Format
{
    public static function price($value)
    {
        return number_format($value, 2);
    }
    public static function toDate($date, $format='Y-m-d')
    {
        return date($format, strtotime($date));
    }
    public static function toTime($date, $format='h:i')
    {
        return date($format, strtotime($date));
    }
    public static function toDateTime($date, $dateFormat='Y-m-d', $timeFormat='h:i A')
    {
        return self::toDate($date, $dateFormat).' | '.self::toDate($date, $timeFormat);
    }
    public static function unit($unit){
        $units = [
            'pcs' => __('pcs'),
            'cm' => __('cm'),
        ];
        if(array_key_exists($unit, $units)){
            return $units[$unit];
        }
        return $unit;
    }
}
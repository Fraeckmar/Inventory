<?php

class Field
{
    public static function customerRoles()
    {
        return [
            'administrator' => __('Administrator'),
            'agent' => __('Agent'),
            'customer' => __('Customer'),
            'driver' => __('Driver'),
            'employee' => __('Employee')
        ];
    }
}
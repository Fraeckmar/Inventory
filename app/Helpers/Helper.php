<?php

use Illuminate\Support\Facades\Auth;

class Helper
{
    public static function datepickerSrc()
    {
        return 'https://unpkg.com/@themesberg/flowbite@1.3.0/dist/datepicker.bundle.js';
    }
    public static function auth_is_admin()
    {
        if(in_array(Auth::user()->role, ['administrator'])) {
            return true;
        } else {
            return false;
        }
    }
}
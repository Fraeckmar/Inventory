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
    public static function pdf_styles()
    {
        ob_start();
        ?>
        <style>
            .table-border td{
                border: 1px solid #000;
                padding: 50px;
            }
        </style>
        <?php
        echo ob_get_clean();
    }
}
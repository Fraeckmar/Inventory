<?php

class Field
{
    /**
     * Customer Roles
     */

    public static function item()
    {
        return [
            'item' => [
                'label' => __('Item'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'description' => [
                'label' => __('Description'),
                'placeholder' => '',
                'type' => 'text',
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'price' => [
                'label' => __('Price'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'balance' => [
                'label' => __('Balance'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'category' => [
                'label' => __('Category'),
                'placeholder' => '',
                'type' => 'select',
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
        ];
    }


    /** 
     * User Fields
    */

    public static function user()
    {
        return [
            'name' => [
                'label' => __('Name'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'email' => [
                'label' => __('Email'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'address' => [
                'label' => __('Address'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
            'role' => [
                'label' => __('Role'),
                'placeholder' => '',
                'type' => 'select',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label']
            ],
        ];
    }

    /** 
     * HTML Input Field Classes
    */

    public static function fieldClass()
    {
        return [
            'item' => [
                'label' => 'text-sm font-medium text-gray-900 block dark:text-gray-300 mb-1',
                'input' => 'border border-gray-300 sm:text-sm rounded-md block w-full p-2'
            ],
            'button' => 'w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase cursor-pointer'
        ];
    }
    /**
     * Customer Roles
     */
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
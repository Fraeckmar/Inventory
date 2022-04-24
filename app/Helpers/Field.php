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
     * ItemBounds Fields
    */
    public static function boundFields($type='inbound')
    {
        $fields['item'] = [
            'label' => __('Item'),
            'placeholder' => '',
            'type' => 'select',                
            'class' => self::fieldClass()['item']['select'],
            'label_class' => self::fieldClass()['item']['label']
        ];
        $fields['qty'] = [
            'label' => __('Quantity'),
            'placeholder' => '',
            'type' => 'number',
            'class' => self::fieldClass()['item']['input'],
            'label_class' => self::fieldClass()['item']['label']
        ];
        if($type == 'outbound') {
            $fields['customer'] = [
                'label' => __('Customer'),
                'placeholder' => '',
                'type' => 'select',
                'class' => self::fieldClass()['item']['select'],
                'label_class' => self::fieldClass()['item']['label']
            ];
        }
        $fields['remarks'] = [
            'label' => __('Remarks'),
            'placeholder' => '',
            'type' => 'textarea',
            'class' => self::fieldClass()['item']['textarea'],
            'label_class' => self::fieldClass()['item']['label']
        ];
        return $fields;
    }

    /** 
     * HTML Input Field Classes
    */
    public static function fieldClass()
    {
        return [
            'item' => [
                'label' => 'text-sm font-medium text-gray-900 block dark:text-gray-300 py-2',
                'input' => 'border border-gray-300 sm:text-sm rounded-md block w-full p-3',
                'select' => 'block border border-grey-light w-full p-3 rounded mb-4',
                'textarea' => 'block border border-grey-light w-full p-3 rounded mb-4'
            ],
            'button' => 'w-full text-center py-4 rounded bg-white text-black border border-black hover:text-white hover:bg-gray-800 focus:outline-none my-1 cursor-pointer'
        ];
    }
    /**
     * Customer Roles
     */
    public static function customerRoles()
    {
        return [
            'administrator' => __('Administrator'),
            // 'agent' => __('Agent'),
            'customer' => __('Customer'),
            // 'driver' => __('Driver'),
            // 'employee' => __('Employee')
        ];
    }

    public static function itemBoundTypes()
    {
        return ['Inbound', 'Outbound'];
    }
}
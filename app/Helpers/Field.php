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
                'key' => 'item',
                'label' => __('Item'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
            'description' => [
                'key' => 'description',
                'label' => __('Description'),
                'placeholder' => '',
                'type' => 'text',
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'options' => []
            ],
            'price' => [
                'key' => 'price',
                'label' => __('Price'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
            'balance' => [
                'key' => 'balance',
                'label' => __('Balance'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
            'category' => [
                'key' => 'category',
                'label' => __('Category'),
                'placeholder' => '',
                'type' => 'select',
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
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
                'key' => 'name',
                'label' => __('Name'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
            'email' => [
                'key' => 'email',
                'label' => __('Email'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
            'address' => [
                'key' => 'address',
                'label' => __('Address'),
                'placeholder' => '',
                'type' => 'text',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'options' => []
            ],
            'role' => [
                'key' => 'role',
                'label' => __('Role'),
                'placeholder' => '',
                'type' => 'select',                
                'class' => self::fieldClass()['item']['input'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ],
        ];
    }

    /** 
     * ItemBounds Fields
    */
    public static function boundFields($type='inbound')
    {
        $fields['item'] = [
            'key' => 'item',
            'label' => __('Item'),
            'placeholder' => '',
            'type' => 'select',                
            'class' => self::fieldClass()['item']['select'],
            'label_class' => self::fieldClass()['item']['label'],
            'value' => '',
            'required' => 'required',
            'options' => []
        ];
        $fields['qty'] = [
            'key' => 'qty',
            'label' => __('Quantity'),
            'placeholder' => '',
            'type' => 'number',
            'class' => self::fieldClass()['item']['input'],
            'label_class' => self::fieldClass()['item']['label'],
            'value' => '',
            'required' => 'required',
            'options' => []
        ];
        if($type == 'outbound') {
            $fields['customer'] = [
                'key' => 'customer',
                'label' => __('Customer'),
                'placeholder' => '',
                'type' => 'select',
                'class' => self::fieldClass()['item']['select'],
                'label_class' => self::fieldClass()['item']['label'],
                'value' => '',
                'required' => 'required',
                'options' => []
            ];
        }
        $fields['remarks'] = [
            'key' => 'remarks',
            'label' => __('Remarks'),
            'placeholder' => '',
            'type' => 'textarea',
            'class' => self::fieldClass()['item']['textarea'],
            'label_class' => self::fieldClass()['item']['label'],
            'value' => '',
            'options' => []
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
                'label' => 'text-sm font-medium text-gray-900 block dark:text-gray-500 py-2',
                'input' => 'border border-gray-500 sm:text-sm rounded-md block w-full p-2 mb-3',
                'number' => 'border border-gray-500 sm:text-sm rounded-md block w-full p-2.5 mb-3',
                'select' => 'block border border-grey-light w-full p-2 rounded mb-3',
                'textarea' => 'block border border-grey-light w-full p-2 rounded mb-3'
            ],
            'button' => 'w-full text-center py-3 rounded bg-blue-800 text-white hover:text-blue-800 hover:bg-white border border-blue-800 my-1 cursor-pointer transition duration-300 ease-in-out'
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
<?php
namespace App\Datatable;

use GenField;

class Datatable {

    private $model = '';
    private $table_actions = [];
    private $table_column_fields = [];
    private $table_column_values = [];
    private $table_filters = [];
    public $table_is_fluid = true;
    public $action_icon_classes = [
        'view' => 'fas fa-eye text-lg transition-colors duration-150 text-green-500 hover:text-green-600',
        'edit' => 'fas fa-edit text-lg transition-colors duration-150 text-blue-500 hover:text-blue-600',
        'delete' => 'fas fa-trash-alt text-lg transition-colors duration-150 text-red-500 hover:text-red-600',
        'report' => 'fas fa-download text-lg transition-colors duration-150 text-cyan-400 hover:text-cyan-600',
        'receipt' => 'fas fa-receipt text-lg transition-colors duration-150 text-cyan-400 hover:text-cyan-600'
    ];

    function __construct($model)
    {
        $this->model = $model;
    }

    function set_table_filters($filters)
    {
        $this->table_filters = $filters;
    }

    function set_table_actions($table_actions)
    {
        $this->table_actions = $table_actions;
    }

    function set_table_column_fields($table_column_fields)
    {
        $this->table_column_fields = $table_column_fields;
    }

    function set_table_column_values($table_column_values)
    {
        $this->table_column_values = $table_column_values;
    }

    function draw()
    {
        $html = '<div class="datatable-container">';    
        if (!empty($this->table_filters)) {
            $html .= '<div class="mb-5 lg:mb-10">';
                $html .= '<div class="block w-full lg:flex">';
                    // Filter Form
                    $html .= '<div id="filter-wrap" class="w-screen-md mb-3 sm:mb-1">';
                        $html .= '<form method="post" action="'.url('orders').'">';
                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="items_filter" value="items_filter"/>';
                            $html .= '<div class="block w-full rounded md:flex">';
                                foreach ($this->table_filters as $filter) {
                                    $fl_type = array_key_exists('type', $filter) ? $filter['type'] : '';
                                    $fl_key = array_key_exists('key', $filter) ? $filter['key'] : '';
                                    $fl_value = array_key_exists('value', $filter) ? $filter['value'] : '';
                                    $fl_options = array_key_exists('options', $filter) ? $filter['options'] : [];
                                    $fl_class = array_key_exists('class', $filter) ? $filter['class'] : '';
                                    $fl_extra = array_key_exists('extra', $filter) ? $filter['extra'] : '';
                                    $fl_label = array_key_exists('label', $filter) ? $filter['label'] : '';
                                    $wrap_class = array_key_exists('wrap_class', $filter) ? $filter['wrap_class'] : '';
                                    $placeholder_c1 = array_key_exists('placeholder_c1', $filter) ? $filter['placeholder_c1'] : '';
                                    $placeholder_c2 = array_key_exists('placeholder_c2', $filter) ? $filter['placeholder_c2'] : '';

                                    $html .= '<div class="px-0.5 '.$wrap_class.'">';
                                        if ($fl_type == 'text') {
                                            $html .= '<input type="'.$fl_type.'" id="'.$fl_key.'" name="'.$fl_key.'" value="'.$fl_value.'" class="'.$fl_class.'" '.$fl_extra.'/>';
                                        }
                                        if ($fl_type == 'select') {
                                            $html .= '<select id="'.$fl_key.'" name="'.$fl_key.'" class="'.$fl_class.'" '.$fl_extra.'>';
                                                foreach ($fl_options as $op_value => $op_label) {
                                                    $selected = $op_value == $fl_value ? 'selected' : '';
                                                    $html .= '<option value="'.$op_value.'" '.$selected.'>'.$op_label.'</option>';
                                                }
                                            $html .= '</select>';
                                        }
                                        if ($fl_type == 'date') {
                                            $html .= '<div date-rangepicker class="flex items-center z-0">';
                                                $html .= '<div class="relative w-full pr-1">';
                                                    $html .= '<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"> <i class="fa fa-calendar text-gray-500"> </i></div>';
                                                    $html .= '<input name="date_from" type="text" class="'.$fl_class.'" placeholder="'.$placeholder_c1.'" autocomplete="off">';
                                                $html .= '</div>';
                                                if (!empty($fl_label)) {
                                                    $html .= '<span class="mx-4 text-gray-500">'.$fl_label.'</span>';
                                                }                                            
                                                $html .= '<div class="relative w-full z-10">';
                                                    $html .= '<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"><i class="fa fa-calendar text-gray-500"> </i></div>';
                                                    $html .= '<input name="date_to" type="text" class="'.$fl_class.'" placeholder="'.$placeholder_c2.'" autocomplete="off">';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                        }
                                    $html .= '</div>';
                                }
                                $html .= '<div class="w-full lg:w-24 sm:w-1/4">';
                                    $html .= '<button type="submit" class="py-2.5 flex items-center justify-center w-full border bg-blue-600 text-white hover:bg-white hover:text-blue-600 hover:border-blue-400 transition duration-300 ease-in-out"> Filter </button>';
                                $html .= '</div>';
                                
                            $html .= '</div>';
                        $html .= '</form>';
                    $html .= '</div>';
                    // Search Form
                    $html .= '<div class="grow"></div>';
                    $html .= '<div id="search-wrap" class="lg:w-72 sm:w-full">';
                        $html .= '<form method="post" action="'.url('orders').'">';
                            $html .= csrf_field();
                            $html .= '<div class="block md:flex">';
                                $html .= '<div class="w-full sm:w-3/4">';
                                    $html .= '<input type="txt" id="search_item" name="search_item" placeholder="Search '.ucwords($this->model).'.." class="p-1.5 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"/>';
                                $html .= '</div>';
                                $html .= '<div class="w-full lg:w-24 sm:w-1/4">';
                                    $html .= '<button type="submit" class="py-2.5 flex items-center justify-center w-full border bg-blue-600 text-white hover:bg-white hover:text-blue-600 hover:border-blue-400 transition duration-300 ease-in-out">';
                                        $html .= '<span class="pr-2"></span><i class="fa fa-search w-full"></i>';
                                    $html .= '</button>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</form>';
                    $html .= '</div>';
                $html .= '</div>';                
            $html .= '</div>';
        }       
        if (!empty($this->table_column_values)) {
            $html .= '<div class="w-full mb-8 overflow-auto md:overflow-hidden rounded-lg shadow-lg">';
                $html .= '<table class="w-full table-stripe">';
                if (!empty($this->table_column_fields)) {
                    $html .= '<thead>';
                        $html .= '<tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">';
                        foreach ($this->table_column_fields as $column_field) {
                            $th_class = array_key_exists('th_class', $column_field) ? $column_field['th_class'] : '';
                            $html .= '<th class="p-3 table-heading '.$th_class.'" align="left">'.$column_field['heading'].'</th>';
                        }
                        if (!empty($this->table_actions)) {
                            $html .= '<th class="p-3 text-center table-heading">'.__('Action').'</th>';
                        }
                        $html .= '</tr>';
                    $html .= '</thead>';
                    
                    $html .= '<tbody class="bg-white">';                        
                    foreach ($this->table_column_values as $column_value) {
                        if (array_key_exists('created_at', $column_value)) {
                            $column_value['created_at'] = date('F j, Y', strtotime($column_value['created_at']));
                        }
                        $html .= '<tr class="text-gray-700">';
                        foreach ($this->table_column_fields as $column_field) {
                            $td_class = array_key_exists('td_class', $column_field) ? $column_field['td_class'] : '';
                            $value = array_key_exists($column_field['key'], $column_value) ? $column_value[$column_field['key']] : '';
                            $html .= '<td class="p-3 border table-data '.$td_class.'" align="left">'.$value.'</td>';
                        }

                        if (!empty($this->table_actions)) {
                            $icons_classes = [
                                'view' => ''
                            ];
                            $action_html = '';
                            foreach ($this->table_actions as $table_action) {
                                $action = array_key_exists('action', $table_action) ? $table_action['action'] : '';
                                $action_model = array_key_exists('model', $table_action) ? $table_action['model'] : '';
                                $key_id = array_key_exists('key_id', $table_action) ? $table_action['key_id'] : 'id';
                                $action_label = array_key_exists('label', $table_action) ? $table_action['label'] : '';
                                $action_class = array_key_exists('class', $table_action) ? $table_action['class'] : '';
                                $action_extra = array_key_exists('extra', $table_action) ? $table_action['extra'] : '';
                                $icon_class_key = !empty($action) ? $action : 'view';
                                $action_title = !empty($action) ?  ucwords($action) : 'View';;
                                if (in_array($table_action['action'], array('report', 'receipt'))) {
                                    $action_html .= '<span class="p-1 '.$action_class.'"'.$action_extra.' title="'.$action_title.'">';
                                        $action_html .= '<i class="cursor-pointer '.$this->action_icon_classes[$icon_class_key].'"></i>'.$action_label;
                                    $action_html .= '</span>';
                                } else {
                                    $action_html .= '<a href="'.url($action_model.'/'.$column_value[$key_id].'/'.$action).'" class="p-1 '.$action_class.'"'.$action_extra.' title="'.$action_title.'">';
                                        $action_html .= '<i class="cursor-pointer '.$this->action_icon_classes[$icon_class_key].'"></i>'.$action_label;
                                    $action_html .= '</a>';
                                }                                
                            }
                            $html .= '<td class="p-3 text-center border table-action">'.$action_html.'</td>';
                        }
                        $html .= '</tr>';
                    }                        
                    $html .= '</tbody>';
                } else {
                    $html .= '<p class="text-lg text-center text-red-500 py-3">Empty column fields!</p>';
                }                
                $html .= '</table>';
            $html .= '</div>';
        } else {
            $html .= '<p class="text-lg text-center text-red-500 py-3">'.__('No items found!').'</p>';
        }
        echo $html;
    }
}
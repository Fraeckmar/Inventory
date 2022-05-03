<?php
namespace App\Datatable;

use GenField;
use App\Helpers\Page;

class Datatable {

    private $model = '';
    private $table_actions = [];
    private $table_column_fields = [];
    private $table_column_values = [];
    private $table_filters = [];
    private $action_variables = [];
    private $pagination;
    public $table_is_fluid = true;
    public $action_icon_classes = [
        'view' => 'fas fa-eye text-lg transition-colors duration-150 text-blue-500 hover:text-blue-600',
        'edit' => 'fas fa-edit text-lg transition-colors duration-150 text-green-500 hover:text-green-600',
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

    function set_action_variables($variables)
    {
        $this->action_variables = $variables;
    }

    function set_table_column_fields($table_column_fields)
    {
        $this->table_column_fields = $table_column_fields;
    }

    function set_table_column_values($table_column_values)
    {
        $this->table_column_values = $table_column_values;
    }

    function set_pagination_links($pagination)
    {
        $this->pagination = $pagination;
    }

    function draw()
    {
        $skip_filters = [];
        $additional_param = [];
        $html = '<div class="datatable-container">';    
        if (!empty($this->table_filters)) {
            $html .= '<div class="mb-5 lg:mb-10">';
                $html .= '<div class="block w-full lg:flex">';
                    // Filter Form
                    $html .= '<div id="filter-wrap" class="w-screen-md mb-3 sm:mb-1">';
                        $html .= '<form method="post" action="'.url($this->model).'">';
                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="items_filter" value="items_filter"/>';
                            $html .= '<div class="block w-full rounded md:flex">';
                                foreach ($this->table_filters as $filter) {
                                    $fl_key = array_key_exists('key', $filter) ? $filter['key'] : '';
                                    if (in_array($fl_key, $skip_filters)) {
                                        continue;
                                    }
                                    $url_param = '';
                                    $fl_type = array_key_exists('type', $filter) ? $filter['type'] : '';                                    
                                    $fl_value = array_key_exists('value', $filter) ? $filter['value'] : '';
                                    $fl_value = empty($fl_value) && isset($_POST[$fl_key]) ? $_POST[$fl_key] : $fl_value;
                                    $fl_value = empty($fl_value) && isset($_GET[$fl_key]) ? $_GET[$fl_key] : $fl_value;
                                    $fl_options = array_key_exists('options', $filter) ? $filter['options'] : [];
                                    $fl_class = array_key_exists('class', $filter) ? $filter['class'] : '';
                                    $fl_extra = array_key_exists('extra', $filter) ? $filter['extra'] : '';
                                    $fl_label = array_key_exists('label', $filter) ? $filter['label'] : '';
                                    $wrap_class = array_key_exists('wrap_class', $filter) ? $filter['wrap_class'] : '';
                                    $placeholder = array_key_exists('placeholder', $filter) ? $filter['placeholder'] : '';
                                    $placeholder = array_key_exists('placeholder', $filter) ? $filter['placeholder'] : '';

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
                                            $fl_value = !empty($fl_value) ? date('m/d/Y', strtotime($fl_value)) : '';
                                            $url_param = empty($url_param) && isset($_POST[$fl_key]) && !empty($_POST[$fl_key]) ? "{$fl_key}=$_POST[$fl_key]" : $url_param;
                                            $url_param = empty($url_param) && isset($_GET[$fl_key]) && !empty($_GET[$fl_key]) ? "{$fl_key}=$_GET[$fl_key]" : $url_param;

                                            $html .= '<div date-rangepicker class="flex items-center z-0">';
                                                $html .= '<div class="relative w-full pr-1">';
                                                    $html .= '<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"> <i class="fa fa-calendar text-gray-500"> </i></div>';
                                                    $html .= '<input name="date_from" type="text" class="'.$fl_class.'" value="'.$fl_value.'" placeholder="'.$placeholder.'" autocomplete="off">';
                                                $html .= '</div>';

                                                $next_iterate = next($this->table_filters);
                                                $next_key = '';
                                                $next_placeholder = '';
                                                
                                                if (!empty($next_iterate) && $next_iterate['type'] == 'date') {
                                                    $next_key = $next_iterate['key'];
                                                    $skip_filters[] = $next_key;
                                                    $next_placeholder = $next_iterate['placeholder'];                                                    
                                                    $nxt_date_val = isset($_POST[$next_key]) && !empty($_POST[$next_key]) ? $_POST[$next_key] : '';
                                                    $nxt_date_val = empty($nxt_date_val) && isset($_GET[$next_key]) && !empty($_GET[$next_key]) ? $_GET[$next_key] : $nxt_date_val;
                                                    if (!empty($nxt_date_val)) {
                                                        $additional_param[$next_key] = "{$next_key}={$nxt_date_val}";    
                                                    }
                                                                                                    

                                                    if (!empty($fl_label)) {
                                                        $html .= '<span class="mx-4 text-gray-500">'.$fl_label.'</span>';
                                                    }                                            
                                                    $html .= '<div class="relative w-full z-10">';
                                                        $html .= '<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"><i class="fa fa-calendar text-gray-500"> </i></div>';
                                                        $html .= '<input name="date_to" type="text" class="'.$fl_class.'" value="'.$nxt_date_val.'" placeholder="'.$next_placeholder.'" autocomplete="off">';
                                                    $html .= '</div>';
                                                }
                                            $html .= '</div>';
                                        }
                                    $html .= '</div>';

                                    $url_param = !empty($fl_value) && empty($url_param) && isset($_POST[$fl_key]) && !empty($_POST[$fl_key]) ? "{$fl_key}=$_POST[$fl_key]" : $url_param;
                                    $url_param = !empty($fl_value) && empty($url_param) && isset($_GET[$fl_key]) && !empty($_GET[$fl_key]) ? "{$fl_key}=$_GET[$fl_key]" : $url_param;
                                    if (!empty($fl_value) && !empty($url_param) && !array_key_exists($fl_key, $additional_param)) {
                                        $additional_param[$fl_key] = $url_param;
                                    }
                                }
                                $html .= '<div class="w-full lg:w-24 sm:w-1/4">';
                                    $html .= '<button type="submit" class="rounded py-3 md:py-2.5 flex items-center justify-center w-full border bg-blue-600 text-white hover:bg-white hover:text-blue-600 hover:border-blue-400 transition duration-300 ease-in-out"> Filter </button>';
                                $html .= '</div>';
                                
                            $html .= '</div>';
                        $html .= '</form>';
                    $html .= '</div>';
                    // Search Form
                    $html .= '<div class="grow"></div>';
                    $html .= '<div id="search-wrap" class="lg:w-64 sm:w-full">';
                        $html .= '<form method="post" action="'.url($this->model).'">';
                            $html .= csrf_field();
                            $html .= '<div class="block md:flex">';
                                $html .= '<div class="w-full sm:w-3/4">';
                                    $html .= '<input type="txt" id="_search" name="_search" placeholder="Search '.ucwords($this->model).'.." class="p-1.5 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"/>';
                                $html .= '</div>';
                                $html .= '<div class="w-full lg:w-24 sm:w-1/4">';
                                    $html .= '<button type="submit" class="rounded py-3 md:py-2.5 flex items-center justify-center w-full border bg-blue-600 text-white hover:bg-white hover:text-blue-600 hover:border-blue-400 transition duration-300 ease-in-out">';
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
            $html .= '<div class="w-full mb-8 overflow-auto md:overflow-hidden rounded-lg shadow-lg font-mono">';
                $html .= '<table class="w-full table-stripe">';
                if (!empty($this->table_column_fields)) {
                    $html .= '<thead>';
                        $html .= '<tr class="thead text-md font-semibold tracking-wide text-left text-gray-900 uppercase">';
                        foreach ($this->table_column_fields as $column_field) {
                            $th_class = array_key_exists('th_class', $column_field) ? $column_field['th_class'] : '';
                            $html .= '<th class="p-3 table-heading '.$th_class.'" align="left">'.$column_field['heading'].'</th>';
                        }
                        if (!empty($this->table_actions)) {
                            $html .= '<th class="p-3 text-center table-heading w-32">'.__('Action').'</th>';
                        }
                        $html .= '</tr>';
                    $html .= '</thead>';
                    
                    $html .= '<tbody class="bg-white">';                        
                    foreach ($this->table_column_values as $column_value) {
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
                                if (!empty($this->action_variables)) {
                                    foreach ($this->action_variables as $variable => $key) {
                                        if (array_key_exists($key, $column_value)) {
                                            $table_action = str_replace($variable, $column_value[$key], $table_action);
                                        }
                                    }
                                }
                                $action = array_key_exists('action', $table_action) ? $table_action['action'] : '';
                                $action_label = array_key_exists('label', $table_action) ? $table_action['label'] : '';
                                $action_class = array_key_exists('class', $table_action) ? $table_action['class'] : '';
                                $action_extra = array_key_exists('extra', $table_action) ? $table_action['extra'] : '';
                                $icon_class_key = !empty($action) ? $action : 'view';
                                $action_title = !empty($action) ?  ucwords($action) : 'View';
                                $action_url = array_key_exists('url', $table_action) ? $table_action['url'] : '#';

                                $action_html .= '<a href="'.url($action_url).'" class="p-1 '.$action_class.'"'.$action_extra.' title="'.$action_title.'">';
                                    $action_html .= '<i class="cursor-pointer '.$this->action_icon_classes[$icon_class_key].'"></i>'.$action_label;
                                $action_html .= '</a>';  
                                if ($action == 'delete') {
                                    $html .= '<form id="delete-'.$this->model.$column_value['id'].'" class="hidden" action="'.url($this->model).'/'.$column_value['id'].'" method="POST">';
                                        $html .= csrf_field();			            		
                                        $html .= '<input type="hidden" name="_method" value="DELETE">';
                                    $html .= '</form>';
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

            // Pagination
            $additional_param = implode('&', $additional_param);
            $additional_param = !empty($additional_param) ? '&'.$additional_param : '';
            if (!empty($this->pagination)) {
                $html.=  Page::pagination($this->pagination, $additional_param);
            }
        } else {
            $html .= '<p class="text-lg text-center text-red-500 py-3">'.__('No items found!').'</p>';
        }
        echo $html;
    }
}
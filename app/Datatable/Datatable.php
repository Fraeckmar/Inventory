<?php
namespace App\Datatable;

class Datatable {

    private $table_actions = [];
    private $table_column_fields = [];
    private $table_column_values = [];
    public $table_is_fluid = true;
    public $action_icon_classes = [
        'view' => 'fas fa-eye text-lg transition-colors duration-150 text-green-500 hover:text-green-600',
        'edit' => 'fas fa-edit text-lg transition-colors duration-150 text-blue-500 hover:text-blue-600',
        'delete' => 'fas fa-trash-alt text-lg transition-colors duration-150 text-red-500 hover:text-red-600',
        'report' => 'fas fa-download text-lg transition-colors duration-150 text-cyan-400 hover:text-cyan-600',
        'receipt' => 'fas fa-receipt text-lg transition-colors duration-150 text-cyan-400 hover:text-cyan-600'
    ];

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
        if (!empty($this->table_column_values)) {
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
        } else {
            $html .= '<p class="text-lg text-center text-red-500 py-3">'.__('No items found!').'</p>';
        }
        echo $html;
    }
}
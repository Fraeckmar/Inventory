<?php

class GenField
{
    public static function input($field=[])
    {
        if (empty($field)) {
            return 'Empty Fields';
        }
        
        $type = array_key_exists('type', $field) ? $field['type'] : 'text';
        $key = array_key_exists('key', $field) ? $field['key'] : '';
        $label = array_key_exists('label', $field) ? $field['label'] : '';
        $value = array_key_exists('value', $field) ? $field['value'] : '';
        $options = array_key_exists('options', $field) ? $field['options'] : [];
        $label_class = array_key_exists('label_class', $field) ? $field['label_class'] : '';
        $required = array_key_exists('required', $field) ? $field['required'] : '';
        $attribues = array_key_exists('attribues', $field) ? $field['attribues'] : '';
        $attribues .= $required;
        $class = array_key_exists('class', $field) ? $field['class'] : '';
        $container_class = array_key_exists('container_class', $field) ? $field['container_class'] : '';
        $options = [ '' => 'Choose..'] + $options;
        $container_margin = !in_array($type, ['submit', 'button']) ? 'mb-3' : '';
        $html = '<div class="form-group '.$container_margin.' '.$container_class.'">';
        if (in_array($field['type'], array('text', 'number'))) {
            $html .= '<label for="'.$key.'" class="'.$label_class.'">'.$label.'</label>';
            $html .= '<input type="'.$type.'" id="'.$key.'" name="'.$key.'" value="'.$value.'" class="'.$class.'" '.$attribues.'/>';
        }
        if (in_array($type, array('submit', 'button'))) {
            $html .= '<button type="'.$type.'" id="'.$key.'" class="'.$class.'" '.$attribues.'>'.$label.'</button>';
        }
        if ($type == 'select') {
            $html .= '<label for="'.$key.'" class="'.$label_class.'">'.$label.'</label>';
            $html .= '<select id="'.$key.'" name="'.$key.'" class="'.$class.'" '.$attribues.'>';
                foreach ($options as $op_value => $op_label) {
                    $selected = $op_value == $value ? 'selected' : '';
                    $html .= '<option value="'.$op_value.'" '.$selected.'>'.$op_label.'</option>';
                }
            $html .= '</select>';
        }
        if ($type == 'textarea') {
            $html .= '<label for="'.$key.'" class="'.$label_class.'" '.$attribues.'>'.$label.'</label>';
            $html .= '<textarea row="3" id="'.$key.'" name="'.$key.'" class="'.$class.'">'.$value.'</textarea>';
        }
        $html .= '</div>';
        echo $html;
    }

    /** 
     * Display successfull message
    */
    public static function notification($msg, $type='success', $inline=false)
    {
        $notificaton = '';
        $text_color = ($type == 'success') ? 'text-success' : 'text-error';
        if ($inline) {
            $notificaton .= '<span class="'.$text_color.'">'.$msg.'</span>';
        } else {
            $notificaton .= '<p class="text-center mt-4 py-4 bg-white shadow-md rounded-sm mx-auto w-fit sm:w-full">';
                $notificaton .= '<span class="'.$text_color.'">'.$msg.'</span>';
            $notificaton .= '</p>';
        }        
        echo $notificaton;
    }

    public static function open($attributes=[])
    {
        return Form::open($attributes);
    }

    public static function end()
    {
        return Form::end();
    }
}
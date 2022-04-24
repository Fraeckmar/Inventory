<?php
class GenField
{
    public static function input($type, $id, $value='', $class='', $options=[])
    {
        $options = [ '' => 'Choose..'] + $options;
        $field = '';
        switch ($type) {
            case 'text':
                $field = Form::text($id, $value, ['class'=>$class]);
                break;
            case 'number':
                $field = Form::number($id, $value, ['class'=>$class]);
                break;
            case 'select':
                $field = Form::select($id, $options, $value, ['class'=>$class]);
                break;
            case 'textarea':
                $field = Form::textarea($id, $value, [
                    'class' => $class,
                    'rows' => 3,
                    'name'  => $id,
                    'id'    => $id
                ]);
                break;
            case 'submit':
                $field = Form::submit($value, ['class' => Field::fieldClass()['button']]);
                break;
            default:
                $field = Form::text($id, $value, ['class'=>$class]);
        }
        return $field;
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
}
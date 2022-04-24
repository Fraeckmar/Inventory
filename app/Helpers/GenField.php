<?php
class GenField
{
    public static function input($type, $id, $value='', $class='', $options=[])
    {
        $options = array_merge(['' => 'Choose..'], $options);
        $field = '';
        switch ($type) {
            case 'text':
                $field = Form::text($id, $value, ['class'=>$class]);
                break;
            case 'select':
                $field = Form::select($id, $options, $value, ['class'=>$class]);
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
    public static function notification($msg, $type='success')
    {
        $notificaton = '';
        if ($type == 'success') {
            $notificaton .= '<p class="text-center mt-4 py-4 bg-white shadow-md rounded-sm mx-auto w-fit sm:w-full">';
                $notificaton .= '<span class="text-green-600">'.$msg.'</span>';
            $notificaton .= '</p>';
        } else if ($type == 'error') {
            $notificaton .= '<p class="text-center mt-4 py-4 bg-white shadow-md rounded-sm mx-auto w-fit sm:w-full">';
                $notificaton .= '<span class="text-red-600">'.$msg.'</span>';
            $notificaton .= '</p>';
        }
        echo $notificaton;
    }
}
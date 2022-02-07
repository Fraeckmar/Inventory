<?php
class GenField
{
    public static function input($type, $id, $value='', $class='', $options=[])
    {
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
    public static function successNotification($msg)
    {
        ob_start();
        ?>
        <p class="text-center mt-4 py-4 bg-white shadow-md rounded-sm mx-auto w-full sm:w-full md:w-2/3">
            <span class="text-green-600"><?php echo $msg; ?></span>
        </p>
        <?php
        echo ob_get_clean();
    }
    /** 
     * Display error message
    */
    public function errorNotification($type, $msg)
    {
        ob_start();
        if($type == 'field'){
            ?>
            <p class="text-red-500 m-0"><?php echo $msg ?></p>
            <?php
        }
        echo ob_get_clean();
    }
}
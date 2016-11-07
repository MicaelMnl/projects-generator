<?php

class FormType {
    
    /**
     * 
     * @param String $name
     * @param String $value
     * @param String $class
     * @param Bolean $required
     * @param String $placholder
     * @return String
     */
    public static function textField($name, $value = false, $class = false, $required = false, $placholder = false)
    {
        
        $field = '<input type=\'text\' id=\''.$name.'\' name=\''.$name.'\' ';

        if($value)
            $field .= 'value='.$value.'';
        
        if($class)
            $field .= 'class='.$class.'';

        if($required)
            $field .= ' required ';

        if($placholder)
            $field .= ' placeholder='.$placholder.' ';

        $field .= '> ';

        return $field;
    }
/**
 * 
 * @param String $name
 * @param String $value
 * @param String $class
 * @param Boloean $required
 * @param String $placholder
 * @return string
 */
    public static function textAreaField($name,$value = false, $class = false, $required = false, $placholder = false)
    {
        
        $field = '<textArea type=\'text\' id=\''.$name.'\' name=\''.$name.'\' ';
        
        if($class)
            $field .= 'class='.$class.'';

        if($required)
            $field .= ' required ';

        if($placholder)
            $field .= ' placeholder='.$placholder.' ';

        $field .= '></textArea> ';

        return $field;
    }
    /**
     * 
     * @param String $name
     * @param String $value
     * @param String $class
     * @return string
     */
    public static function submitField($name,$value = false, $class = false)
    {
        
        $field = '<input type=\'submit\' id=\''.$name.'\'  name=\''.$name.'\'';

        if($value)
            $field .= 'value='.$value.'';
        
        if($class)
            $field .= 'class='.$class.'';

        $field .= '></input> ';

        return $field;
    }
    
    /**
     * 
     * @param String $name
     * @param String $value
     * @param String $class
     * @param Bolean $required
     * @param Bolean $checked
     * @return string
     */
    public static function bool($name,$value = false, $class = false, $required = false, $checked = false)
    {
        $field = '<input type=\'checkbox\' id=\''.$name.'\'  name=\''.$name.'\'';

        if($value)
            $field .= 'value='.$value.'';
        
        if($class)
            $field .= 'class='.$class.'';

        if($required)
            $field .= ' required ';

        if($checked)
            $field .= ' checked="checked" ';

        $field .= '></input> ';

        return $field;
    }

}

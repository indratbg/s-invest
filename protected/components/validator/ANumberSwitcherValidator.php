<?php
class ANumberSwitcherValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
        $value = str_replace(",","",strval($object->$attribute));
        $object->$attribute = $value;
    }
}
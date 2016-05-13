<?php

use CupOfTea\Enums\HasMagicEnums;

class ParentClass
{
    public function __call($method, $args)
    {
        return $method;
    }
}
class HasMagicEnumsStub extends ParentClass
{
    use HasMagicEnums;
    
    const TYPE_DINE_IN = 1;
    const TYPE_TAKEAWAY = 2;
    
    public function __construct($properties = [])
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }
}

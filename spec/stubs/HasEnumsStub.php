<?php

use CupOfTea\Enums\HasEnums;

class HasEnumsStub
{
    use HasEnums;
    
    const TYPE_DINE_IN = 1;
    const TYPE_TAKEAWAY = 2;
    
    const STATUS_NEW = 1;
    const STATUS_SERVED = 2;
    const STATUS_CANCELLED = 3;
    
    const DRINK_TYPE_WATER = 1;
    const DRINK_TYPE_SODA = 2;
    const DRINK_TYPE_ALCOHOL = 3;
    
    public function __construct($properties = [])
    {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }
}

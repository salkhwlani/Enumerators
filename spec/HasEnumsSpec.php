<?php

namespace spec\CupOfTea\Enums;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

require_once 'stubs/HasEnumsStub.php';

class HasEnumsSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('HasEnumsStub');
        $this->beConstructedWith([
            'type' => 2,
            'drinkType' => 1,
        ]);
    }
    
    function it_returns_all_enums()
    {
        $this->enums()->shouldReturn([
            'TYPE_DINE_IN' => 1,
            'TYPE_TAKEAWAY' => 2,
            
            'STATUS_NEW' => 1,
            'STATUS_SERVED' => 2,
            'STATUS_CANCELLED' => 3,
            
            'DRINK_TYPE_WATER' => 1,
            'DRINK_TYPE_SODA' => 2,
            'DRINK_TYPE_ALCOHOL' => 3,
        ]);
    }
    
    function it_returns_enums_for_group()
    {
        $this->enums('TYPE')->shouldReturn([
            'TYPE_DINE_IN' => 1,
            'TYPE_TAKEAWAY' => 2,
        ]);
        
        $this->enums('status')->shouldReturn([
            'STATUS_NEW' => 1,
            'STATUS_SERVED' => 2,
            'STATUS_CANCELLED' => 3,
        ]);
    }
    
    function it_can_check_if_a_value_is_a_valid_enum_value()
    {
        $this->isValidEnumValue(1)->shouldBe(true);
        $this->isValidEnumValue(5)->shouldBe(false);
    }
    
    function it_can_check_if_a_value_is_a_valid_enum_value_for_a_group()
    {
        $this->isValidEnumValue(1, 'type')->shouldBe(true);
        $this->isValidEnumValue(3, 'type')->shouldBe(false);
        $this->isValidEnumValue(3, 'status')->shouldBe(true);
    }
    
    function it_should_not_allow_invalid_enum_values()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringIsValidEnumValue(-1);
    }
    
    function it_can_check_if_a_value_is_a_valid_enum_key()
    {
        $this->isValidEnumKey('TYPE_DINE_IN')->shouldBe(true);
        $this->isValidEnumKey('FOO_BAR')->shouldBe(false);
    }
    
    function it_can_check_if_an_instance_is_a_specified_enumerator()
    {
        $this->isEnum('TYPE_DINE_IN')->shouldBe(false);
        $this->isEnum('STATUS_NEW')->shouldBe(false);
        
        $this->isEnum('TYPE_TAKEAWAY')->shouldBe(true);
        $this->isEnum('TAKEAWAY', 'TYPE')->shouldBe(true);
        $this->isEnum('typeTakeaway')->shouldBe(true);
        
        $this->isEnum('DRINK_TYPE_WATER')->shouldBe(true);
        //$this->isEnum('drinkTypeWater')->shouldBe(true);
    }
    
    function it_should_not_allow_invalid_enum_keys()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringIsEnum('FOO_BAR');
    }
}

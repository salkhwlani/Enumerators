<?php

namespace spec\CupOfTea\Enums;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

require_once 'stubs/HasMagicEnumsStub.php';

class HasMagicEnumsSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('HasMagicEnumsStub');
        $this->beConstructedWith([
            'type' => 2,
        ]);
    }
    
    function it_can_check_if_an_instance_is_a_specified_enumerator_via_magic_isEnum_call()
    {
        $this->isTypeTakeaway()->shouldBe(true);
        $this->isTypeDineIn()->shouldBe(false);
    }
    
    function it_forwards_other_calls_to_parent_magic_call()
    {
        $this->someMethod()->shouldReturn('someMethod');
    }
}

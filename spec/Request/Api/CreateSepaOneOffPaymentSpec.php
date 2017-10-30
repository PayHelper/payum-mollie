<?php

declare(strict_types=1);

namespace spec\PayHelper\Payum\Mollie\Request\Api;

use PayHelper\Payum\Mollie\Request\Api\CreateSepaOneOffPayment;
use Payum\Core\Request\Generic;
use PhpSpec\ObjectBehavior;

final class CreateSepaOneOffPaymentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateSepaOneOffPayment::class);
    }

    function it_should_extends_generic_request()
    {
        $this->shouldHaveType(Generic::class);
    }
}

<?php

namespace spec\Sourcefabric\Payum\Mollie\Request\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\Generic;
use Sourcefabric\Payum\Mollie\Request\Api\GetPaymentDetails;
use PhpSpec\ObjectBehavior;

final class GetPaymentDetailsSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new ArrayObject(), 'tr_123456');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GetPaymentDetails::class);
    }

    function it_should_extends_generic_request()
    {
        $this->shouldHaveType(Generic::class);
    }

    function it_should_return_payment_id()
    {
        $this->getPaymentId()->shouldBeEqualTo('tr_123456');
    }
}

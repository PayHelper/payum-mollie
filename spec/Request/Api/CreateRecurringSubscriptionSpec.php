<?php

namespace spec\Sourcefabric\Payum\Mollie\Request\Api;

use Payum\Core\Request\Generic;
use Sourcefabric\Payum\Mollie\Request\Api\CreateRecurringSubscription;
use PhpSpec\ObjectBehavior;

final class CreateRecurringSubscriptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateRecurringSubscription::class);
    }

    function it_should_extends_generic_request()
    {
        $this->shouldHaveType(Generic::class);
    }
}

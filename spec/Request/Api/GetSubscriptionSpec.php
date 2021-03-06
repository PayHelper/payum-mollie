<?php

declare(strict_types=1);

namespace spec\PayHelper\Payum\Mollie\Request\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\Generic;
use PayHelper\Payum\Mollie\Request\Api\GetSubscription;
use PhpSpec\ObjectBehavior;

final class GetSubscriptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new ArrayObject(), 'sub_hgyt65');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GetSubscription::class);
    }

    function it_should_extends_generic_request()
    {
        $this->shouldHaveType(Generic::class);
    }

    function it_should_return_subscription_id()
    {
        $this->getSubscriptionId()->shouldBeEqualTo('sub_hgyt65');
    }
}

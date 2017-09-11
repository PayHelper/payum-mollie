<?php

declare(strict_types=1);

namespace spec\Sourcefabric\Payum\Mollie;

use Payum\Core\GatewayFactory;
use Payum\Core\GatewayFactoryInterface;
use Sourcefabric\Payum\Mollie\MollieGatewayFactory;
use PhpSpec\ObjectBehavior;

final class MollieGatewayFactorySpec extends ObjectBehavior
{
    function let(GatewayFactoryInterface $gatewayFactory)
    {
        $this->beConstructedWith([], $gatewayFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MollieGatewayFactory::class);
    }

    function it_should_extend_base_gateway()
    {
        $this->shouldHaveType(GatewayFactory::class);
    }
}

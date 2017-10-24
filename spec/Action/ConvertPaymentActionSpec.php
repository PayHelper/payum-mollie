<?php

declare(strict_types=1);

namespace spec\PayHelper\Payum\Mollie\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use PayHelper\Payum\Mollie\Action\ConvertPaymentAction;
use PhpSpec\ObjectBehavior;

final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_should_implement_interface()
    {
        $this->shouldImplement(ActionInterface::class);
        $this->shouldImplement(GatewayAwareInterface::class);
    }

    function it_supports(Convert $request, PaymentInterface $payment)
    {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $this->supports($request)->shouldReturn(true);
    }
}

<?php

declare(strict_types=1);

namespace spec\Sourcefabric\Payum\Mollie\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Notify;
use Sourcefabric\Payum\Mollie\Action\Api\BaseApiAwareAction;
use Sourcefabric\Payum\Mollie\Action\NotifyAction;
use PhpSpec\ObjectBehavior;

final class NotifyActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NotifyAction::class);
        $this->shouldHaveType(BaseApiAwareAction::class);
    }

    function it_should_implement_interface()
    {
        $this->shouldImplement(ActionInterface::class);
    }

    function it_supports(Notify $request)
    {
        $request->getModel()->willReturn(new \ArrayObject());

        $this->supports($request)->shouldReturn(true);
    }
}

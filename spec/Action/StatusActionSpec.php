<?php

declare(strict_types=1);

namespace spec\PayHelper\Payum\Mollie\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use PayHelper\Payum\Mollie\Action\StatusAction;
use PhpSpec\ObjectBehavior;

final class StatusActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StatusAction::class);
    }

    function it_should_implement_interface()
    {
        $this->shouldImplement(ActionInterface::class);
    }

    function it_should_mark_subscription_as_authorized(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'active']]));
        $request->markAuthorized()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_subscription_as_pending(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'pending']]));
        $request->markPending()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_subscription_as_cancelled(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'cancelled']]));
        $request->markCanceled()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_subscription_as_captured(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'completed']]));
        $request->markCaptured()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_subscription_as_suspended(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'suspended']]));
        $request->markSuspended()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_subscription_as_uknown(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['subscription' => ['status' => 'fake']]));
        $request->markUnknown()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_new(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject([]));
        $request->markNew()->shouldBeCalled();

        $this->execute($request);

        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'open']]));
        $request->markNew()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_captured(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'paid']]));
        $request->markCaptured()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_cancelled(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'cancelled']]));
        $request->markCanceled()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_pending(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'pending']]));
        $request->markPending()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_failed(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'failed']]));
        $request->markFailed()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_payedout(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'paidout']]));
        $request->markPayedout()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_expired(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'expired']]));
        $request->markExpired()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_refunded(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'refunded']]));
        $request->markRefunded()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_payment_as_unknown(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject(['payment' => ['status' => 'fake']]));
        $request->markUnknown()->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports(GetStatusInterface $request)
    {
        $request->getModel()->willReturn(new \ArrayObject());

        $this->supports($request)->shouldReturn(true);
    }
}

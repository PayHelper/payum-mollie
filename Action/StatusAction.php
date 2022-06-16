<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action;

use Mollie\Api\Types\PaymentStatus;
use Mollie\Api\Types\SubscriptionStatus;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (isset($model['subscription'])) {
            switch ($model['subscription']['status']) {
                case SubscriptionStatus::STATUS_ACTIVE:
                    $request->markAuthorized();

                    break;
                case SubscriptionStatus::STATUS_PENDING:
                    $request->markPending();

                    break;
                case SubscriptionStatus::STATUS_CANCELLED:
                    $request->markCanceled();

                    break;
                case SubscriptionStatus::STATUS_COMPLETED:
                    $request->markCaptured();

                    break;
                case SubscriptionStatus::STATUS_SUSPENDED:
                    $request->markSuspended();

                    break;
                default:
                    $request->markUnknown();

                    break;
            }

            return;
        }

        if (!isset($model['payment'])) {
            $request->markNew();

            return;
        }

        switch ($model['payment']['status']) {
            case PaymentStatus::STATUS_OPEN:
                $request->markNew();

                break;
            case PaymentStatus::STATUS_PAID:
                $request->markCaptured();

                break;
            case PaymentStatus::STATUS_CANCELLED:
                $request->markCanceled();

                break;
            case PaymentStatus::STATUS_PENDING:
                $request->markPending();

                break;
            case PaymentStatus::STATUS_FAILED:
                $request->markFailed();

                break;
            case PaymentStatus::STATUS_PAIDOUT:
                $request->markPayedout();

                break;
            case PaymentStatus::STATUS_EXPIRED:
                $request->markExpired();

                break;
            case PaymentStatus::STATUS_REFUNDED:
                $request->markRefunded();

                break;
            default:
                $request->markUnknown();

                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

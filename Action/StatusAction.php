<?php

namespace Sourcefabric\Payum\Mollie\Action;

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
                case \Mollie_API_Object_Customer_Subscription::STATUS_ACTIVE:
                    $request->markAuthorized();

                    break;
                case \Mollie_API_Object_Customer_Subscription::STATUS_PENDING:
                    $request->markPending();

                    break;
                case \Mollie_API_Object_Customer_Subscription::STATUS_CANCELLED:
                    $request->markCanceled();

                    break;
                case \Mollie_API_Object_Customer_Subscription::STATUS_COMPLETED:
                    $request->markCaptured();

                    break;
                case \Mollie_API_Object_Customer_Subscription::STATUS_SUSPENDED:
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
            case \Mollie_API_Object_Payment::STATUS_OPEN:
                $request->markNew();

                break;
            case \Mollie_API_Object_Payment::STATUS_PAID:
                $request->markCaptured();

                break;
            case \Mollie_API_Object_Payment::STATUS_CANCELLED:
                $request->markCanceled();

                break;
            case \Mollie_API_Object_Payment::STATUS_PENDING:
                $request->markPending();

                break;
            case \Mollie_API_Object_Payment::STATUS_FAILED:
                $request->markFailed();

                break;
            case \Mollie_API_Object_Payment::STATUS_PAIDOUT:
                $request->markPayedout();

                break;
            case \Mollie_API_Object_Payment::STATUS_EXPIRED:
                $request->markExpired();

                break;
            case \Mollie_API_Object_Payment::STATUS_REFUNDED:
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

<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Cancel;

class CancelRecurringSubscriptionAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (\Mollie_API_Object_Customer_Subscription::STATUS_CANCELLED ===
            $model['subscription']['status']) {
            return;
        }

        $cancelledSubscription = $this->api->customers_subscriptions->withParentId($model['customer']['id'])
            ->cancel($model['subscription']['id']);

        $model->replace(['subscription' => (array) $cancelledSubscription]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        // cancel only subscriptions
        return
            $request instanceof Cancel &&
            $request->getModel() instanceof \ArrayAccess &&
            isset($request->getModel()['subscription']) &&
            'subscription' === $request->getModel()['subscription']['resource']
        ;
    }
}

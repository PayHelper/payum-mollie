<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Mollie\Api\Resources\Subscription;
use Mollie\Api\Types\SubscriptionStatus;
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

        if (SubscriptionStatus::STATUS_CANCELED ===
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

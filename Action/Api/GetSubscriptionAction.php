<?php

namespace Sourcefabric\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Sourcefabric\Payum\Mollie\Request\Api\GetSubscription;

class GetSubscriptionAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @throws \Payum\Core\Exception\LogicException
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (null === $request->getSubscriptionId()) {
            throw new LogicException('"subscriptionId" has to be provided to get a subscription');
        }

        $response = $this->api->customers_subscriptions
            ->withParentId($model['customer']['id'])
            ->get($request->getSubscriptionId());

        $model->replace(['subscription' => (array) $response]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetSubscription &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

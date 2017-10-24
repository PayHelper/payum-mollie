<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use PayHelper\Payum\Mollie\Request\Api\CreateRecurringSubscription;

class CreateRecurringSubscriptionAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = \Mollie_API_Client::class;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['interval', 'startDate', 'customer']);

        $subscription = $this->api->customers_subscriptions->withParentId($model['customer']['id'])->create([
            'amount' => $model['amount'],
            'interval' => $model['interval'],
            'description' => sprintf('Recurring subscription for customer %s', $model['customer']['id']),
            'method' => $model['method'],
            'webhookUrl' => $model['notifyUrl'],
            'startDate' => $model['startDate'],
        ]);

        $model->replace(['subscription' => (array) $subscription]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateRecurringSubscription &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

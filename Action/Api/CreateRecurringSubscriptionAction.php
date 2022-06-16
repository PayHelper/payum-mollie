<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Mollie\Api\MollieApiClient;
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
        $this->apiClass = MollieApiClient::class;
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
            'amount' => [
                'value' => sprintf('%.2f', $model['amount']),
                'currency' => $model['currency'],
            ],
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

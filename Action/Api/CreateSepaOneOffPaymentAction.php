<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Mollie\Api\MollieApiClient;
use Mollie\Api\Types\MandateStatus;
use Mollie\Api\Types\PaymentMethod;
use Mollie\Api\Types\SequenceType;
use PayHelper\Payum\Mollie\Request\Api\CreateSepaOneOffPayment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;

class CreateSepaOneOffPaymentAction implements ActionInterface, ApiAwareInterface
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

        $model->validateNotEmpty(['sepaIban', 'sepaHolder', 'customer']);

        $response = $this->api->customers_mandates->withParentId($model['customer']['id'])->create([
            'method' => PaymentMethod::DIRECTDEBIT,
            'consumerAccount' => $model['sepaIban']->get(),
            'consumerName' => $model['sepaHolder']->get(),
        ]);

        $mandate = ArrayObject::ensureArrayObject($response);

        if (MandateStatus::STATUS_VALID !== $mandate['status']) {
            throw new LogicException('Mandate is invalid.');
        }

        $model->replace(['mandate' => (array) $mandate]);

        $payment = $this->api->payments->create([
            'amount' => [
                'value' => sprintf('%.2f', $model['amount']),
                'currency' => $model['currency'],
            ],
            'description' => 'An on-demand payment (one-off)',
            'recurringType' => SequenceType::SEQUENCETYPE_RECURRING,
            'redirectUrl' => $model['returnUrl'],
            'webhookUrl' => $model['notifyUrl'],
            'customerId' => $model['customer']['id'],
        ]);

        $model->replace(['payment' => (array) $payment]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateSepaOneOffPayment &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

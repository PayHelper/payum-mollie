<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use PayHelper\Payum\Mollie\Request\Api\CreateSepaOneOffPayment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class CreateSepaOneOffPaymentAction implements ActionInterface, ApiAwareInterface
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

        $model->validateNotEmpty(['sepaIban', 'sepaHolder', 'customer']);

        $response = $this->api->customers_mandates->withParentId($model['customer']['id'])->create([
            'method' => \Mollie_API_Object_Method::DIRECTDEBIT,
            'consumerAccount' => $model['sepaIban']->get(),
            'consumerName' => $model['sepaHolder']->get(),
        ]);

        $mandate = ArrayObject::ensureArrayObject($response);

        if (\Mollie_API_Object_Customer_Mandate::STATUS_VALID !== $mandate['status']) {
            // mandate invalid
            dump($mandate);
            die;
        }

        $model->replace(['mandate' => (array) $mandate]);

        $payment = $this->api->payments->create([
            'amount' => $model['amount'],
            'description' => 'An on-demand payment (one-off)',
            'recurringType' => \Mollie_API_Object_Payment::RECURRINGTYPE_RECURRING,
            'redirectUrl' => $model['returnUrl'],
            'webhookUrl' => 'https://requestb.in/1fe0vmv1', // $model['notifyUrl']
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

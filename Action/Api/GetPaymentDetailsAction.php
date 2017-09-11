<?php

declare(strict_types=1);

namespace Sourcefabric\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Sourcefabric\Payum\Mollie\Request\Api\GetPaymentDetails;

class GetPaymentDetailsAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (null === $request->getPaymentId()) {
            throw new LogicException('"id" has to be provided to get a payment');
        }

        $response = $this->api->payments->get($request->getPaymentId());

        $model->replace(['payment' => (array) $response]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return $request instanceof GetPaymentDetails;
    }
}

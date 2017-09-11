<?php

declare(strict_types=1);

namespace Sourcefabric\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Sourcefabric\Payum\Mollie\Request\Api\CreateCapture;

class CreateCaptureAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $result = $this->api->payments->create([
            'amount' => $model['amount'],
            'description' => $model['description'],
            'redirectUrl' => $model['returnUrl'],
            'webhookUrl' => $model['notifyUrl'],
            'method' => \Mollie_API_Object_Method::CREDITCARD,
            'metadata' => $model['metadata'],
        ]);

        $model->replace(['payment' => (array) $result]);

        throw new HttpRedirect($result->links->paymentUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateCapture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

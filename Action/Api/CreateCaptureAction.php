<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use PayHelper\Payum\Mollie\Request\Api\CreateCapture;

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
            'amount' => [
                'value' => sprintf('%.2f', $model['amount']),
                'currency' => $model['currency'],
            ],
            'description' => $model['description'],
            'redirectUrl' => $model['returnUrl'],
            'webhookUrl' => $model['notifyUrl'],
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

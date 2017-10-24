<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use PayHelper\Payum\Mollie\Action\Api\BaseApiAwareAction;
use PayHelper\Payum\Mollie\Request\Api\GetPaymentDetails;
use PayHelper\Payum\Mollie\Request\Api\GetSubscription;

class NotifyAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        /* @var $request Notify */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $postParams = [];
        parse_str($httpRequest->content, $postParams);
        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (isset($postParams['id']) && !empty($postParams['id'])) {
            $this->gateway->execute(new GetPaymentDetails($model, $postParams['id']));

            throw new HttpResponse('OK', 200);
        }

        if (isset($postParams['subscriptionId']) && !empty($postParams['subscriptionId'])) {
            // user has been charged again etc.
            $this->gateway->execute(new GetSubscription($model, $postParams['subscriptionId']));

            throw new HttpResponse('OK', 200);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

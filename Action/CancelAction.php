<?php

namespace Sourcefabric\Payum\Mollie\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Cancel;

class CancelAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Cancel $request
     */
    public function execute($request)
    {
        // cancel normal payments

        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        throw new LogicException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Cancel &&
            $request->getModel() instanceof \ArrayAccess &&
            isset($request->getModel()['payment']) &&
            'payment' === $request->getModel()['payment']['resource']
        ;
    }
}

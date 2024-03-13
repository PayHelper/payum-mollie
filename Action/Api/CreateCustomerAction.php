<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Mollie\Api\MollieApiClient;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use PayHelper\Payum\Mollie\Request\Api\CreateCustomer;

class CreateCustomerAction implements ActionInterface, ApiAwareInterface
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

        $data = ['name' => ''];

        if (isset($model['email'])) {
            $data['email'] = $model['email'];
        }
        $customer = $this->api->customers->create($data);

        $model->replace(['customer' => (array) $customer]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateCustomer &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

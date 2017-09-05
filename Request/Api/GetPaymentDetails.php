<?php

namespace Sourcefabric\Payum\Mollie\Request\Api;

use Payum\Core\Request\Generic;

class GetPaymentDetails extends Generic
{
    /**
     * @var string
     */
    protected $paymentId;

    /**
     * GetSubscription constructor.
     *
     * @param mixed  $model
     * @param string $paymentId
     */
    public function __construct($model, $paymentId)
    {
        parent::__construct($model);

        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }
}

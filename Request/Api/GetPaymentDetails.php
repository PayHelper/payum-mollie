<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Request\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\Generic;

class GetPaymentDetails extends Generic
{
    /**
     * @var string
     */
    protected $paymentId;

    /**
     * GetPaymentDetails constructor.
     *
     * @param ArrayObject $model
     * @param string      $paymentId
     */
    public function __construct(ArrayObject $model, string $paymentId)
    {
        parent::__construct($model);

        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }
}

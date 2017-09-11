<?php

declare(strict_types=1);

namespace Sourcefabric\Payum\Mollie\Request\Api;

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
     * @param \ArrayAccess $model
     * @param string       $paymentId
     */
    public function __construct(\ArrayAccess $model, string $paymentId)
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

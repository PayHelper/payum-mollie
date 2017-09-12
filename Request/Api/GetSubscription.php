<?php

declare(strict_types=1);

namespace Sourcefabric\Payum\Mollie\Request\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\Generic;

class GetSubscription extends Generic
{
    /**
     * @var string
     */
    protected $subscriptionId;

    /**
     * GetSubscription constructor.
     *
     * @param ArrayObject $model
     * @param string      $subscriptionId
     */
    public function __construct(ArrayObject $model, string $subscriptionId)
    {
        parent::__construct($model);

        $this->subscriptionId = $subscriptionId;
    }

    /**
     * @return string
     */
    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }
}

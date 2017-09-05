<?php

namespace Sourcefabric\Payum\Mollie\Request\Api;

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
     * @param mixed  $model
     * @param string $subscriptionId
     */
    public function __construct($model, $subscriptionId)
    {
        parent::__construct($model);

        $this->subscriptionId = $subscriptionId;
    }

    /**
     * @return string
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }
}

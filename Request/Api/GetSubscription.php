<?php

declare(strict_types=1);

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
     * @param array  $model
     * @param string $subscriptionId
     */
    public function __construct(array $model, string $subscriptionId)
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

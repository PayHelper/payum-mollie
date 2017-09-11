<?php

declare(strict_types=1);

namespace Sourcefabric\Payum\Mollie;

use Sourcefabric\Payum\Mollie\Action\Api\CancelRecurringSubscriptionAction;
use Sourcefabric\Payum\Mollie\Action\Api\CreateCaptureAction;
use Sourcefabric\Payum\Mollie\Action\Api\CreateCustomerAction;
use Sourcefabric\Payum\Mollie\Action\Api\CreateRecurringSubscriptionAction;
use Sourcefabric\Payum\Mollie\Action\Api\CreateSepaMandateAction;
use Sourcefabric\Payum\Mollie\Action\Api\GetPaymentDetailsAction;
use Sourcefabric\Payum\Mollie\Action\Api\GetSubscriptionAction;
use Sourcefabric\Payum\Mollie\Action\CancelAction;
use Sourcefabric\Payum\Mollie\Action\ConvertPaymentAction;
use Sourcefabric\Payum\Mollie\Action\CaptureAction;
use Sourcefabric\Payum\Mollie\Action\NotifyAction;
use Sourcefabric\Payum\Mollie\Action\RefundAction;
use Sourcefabric\Payum\Mollie\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class MollieGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'mollie',
            'payum.factory_title' => 'Mollie',
            'payum.template.sepa_mandate_confirmation' => '@payum_mollie/Action/sepa_mandate_confirmation.html.twig',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.api.capture' => new CreateCaptureAction(),
            'payum.action.api.create_customer' => new CreateCustomerAction(),
            'payum.action.api.cancel_recurring_subscription' => new CancelRecurringSubscriptionAction(),
            'payum.action.api.create_sepa_mandate' => function (ArrayObject $config) {
                return new CreateSepaMandateAction($config['payum.template.sepa_mandate_confirmation']);
            },
            'payum.action.api.create_recurring_subscription' => new CreateRecurringSubscriptionAction(),
            'payum.action.api.get_payment_details' => new GetPaymentDetailsAction(),
            'payum.action.api.get_subscription_details' => new GetSubscriptionAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'apiKey',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $client = new \Mollie_API_Client();
                $client->setApiKey($config['apiKey']);

                return $client;
            };
        }
    }
}

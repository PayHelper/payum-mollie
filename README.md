# Payum Mollie Extension [![Build Status](https://travis-ci.org/PayHelper/payum-mollie.svg?branch=master)](https://travis-ci.org/PayHelper/payum-mollie) [![StyleCI](https://styleci.io/repos/101880694/shield?branch=master)](https://styleci.io/repos/101880694)

The Payum extension. It provides [Mollie](https://www.mollie.com/en/) payment integration.

Getting Started
===============

Requirements
----------------

This library requires PHP 7.1 or higher.

Installing the extension
------------------------

Install this extension as a Composer dependency by requiring it in a `composer.json` file:

```bash
composer require webbaard/payum-mollie
```

Register the Mollie Payum factory using `PayumBuilder`:

```php
use Payum\Core\GatewayFactoryInterface;
use PayHelper\Payum\Mollie\MollieGatewayFactory;

$payumBuilder->addGatewayFactory('mollie', function(array $config, GatewayFactoryInterface $gatewayFactory) {
    return new MollieGatewayFactory($config, $gatewayFactory);
});

$payumBuilder->addGateway('mollie', [
    'factory' => 'mollie',
    'apiKey' => 'api123456', // change this
    'method' => 'creditcard', // one of directdebit, creditcard or directdebit_oneoff
]);
``` 

To work properly, Mollie gateway requires some additional fields being passed to the details of the payment. See the section below.

Supported methods
-----------------

Check the documentation for each payment method to find out which fields are requred in order to make use of the methods.

- [Recurring SEPA Direct Debit](Resources/doc/sepa_direct_debit.md)
- [One-off SEPA Direct Debit](Resources/doc/oneoff_sepa_direct_debit.md)
- [Checkout Mollie](Resources/doc/checkout_mollie.md) For this method the customer will be forward to Mollie checkout page and he could choose any enabled payment method like credit card, Bancontact, etc.

Symfony integration
-------------------

1. PayumBundle installation

In order to use that extension with the Symfony, you will need to install [PayumBundle](https://github.com/Payum/PayumBundle) first and configure it according to its documentation.

```bash
composer require payum/payum-bundle ^2.0
```

2. Register Mollie Gateway Factory as a service

```yaml
# app/config/services.yml

services:
    app.payum.mollie.factory:
        class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
        arguments: [PayHelper\Payum\Mollie\MollieGatewayFactory]
        tags:
            - { name: payum.gateway_factory_builder, factory: mollie }
```

3. Configure the gateway

```yaml
# app/config/config.yml

payum:
    gateways:
        mollie:
            factory: mollie
            apiKey: api123456 # change this
            method: creditcard # one of directdebit, creditcard or directdebit_oneoff
```

4. Gateway usage

Retrieve it from the `payum` service:

```php
$gateway = $this->get('payum')->getGeteway('mollie');
```

License
-------
This library is licensed under the [GNU GPLv3](LICENSE) license.

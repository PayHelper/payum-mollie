# Recurring SEPA Direct Debit

Recurring SEPA Direct Debits are supported.

* [Capture](#capture)
* [Create Mandate](#create-mandate)
* [Symfony integration](#symfony-integration)

## Capture

**Note** that Authorization is done via Capture request.
First, the payment is authorized via Capture request and 
then it is automatically captured via Notify request.

```php
use Payum\Core\Request\Capture;

$payment = [];
$payment['method'] = 'directdebit';
$payment['interval'] = '1 month'; // 1 month, 3 months, 1 year etc.
$payment['startDate'] = '2017-09-01'; // in yyyy-mm-dd format or leave empty to set current date
$payment['sepaBic'] = 'AAABBBCF2'; // optional
$payment['email'] = 'email@example.com'; // optional

$payum
    ->getGateway('mollie')
    ->execute(new Capture($payment));
```

## Create Mandate

```php
use Payum\Core\Request\Capture;
use Payum\Core\Security\SensitiveValue;
use PayHelper\Payum\Mollie\Request\Api\CreateSepaMandate;

$payment = [];
$payment['method'] = 'directdebit';
$payment['sepaIban'] = SensitiveValue::ensureSensitive('DE69103442341234545489');
$payment['sepaHolder'] = 'Doe';
$payment['sepaBic'] = 'AAABBBCF2'; // optional
$payment['email'] = 'email@example.com'; // optional

$payum
    ->getGateway('mollie')
    ->execute(new CreateSepaMandate($payment));
```

# Symfony integration:

```php
<?php

//src/Acme/PaymentBundle/Controller
namespace AcmeDemoBundle\Controller;

use Payum\Core\Security\SensitiveValue;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    public function prepareAction(Request $request)
    {
        $gatewayName = 'mollie';

        $storage = $this->get('payum')->getStorage('Acme\PaymentBundle\Entity\PaymentDetails');

        /** @var \Acme\PaymentBundle\Entity\PaymentDetails $details */
        $details = $storage->create();
        $details['method'] = 'directdebit';
        $details['currency'] = 'EUR';
        $details['amount'] = 5;
        $details['sepaIban'] = SensitiveValue::ensureSensitive('DE69103442341234545489');
        $details['sepaHolder'] = 'Doe';
        $payment['sepaBic'] = 'AAABBBCF2'; // optional
        $payment['email'] = 'email@example.com'; // optional
        $details['interval'] = '1 month'; // 1 month, 3 months, 1 year etc.
        $details['startDate'] = '2017-09-01'; // in yyyy-mm-dd format or leave empty to set current date

        $storage->update($details);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $details,
            'acme_payment_done' // the route to redirect after capture;
        );

        return $this->redirect($captureToken->getTargetUrl());
    }
}

```

# One-off SEPA Direct Debit

One-off SEPA Direct Debits are supported.

* [Capture](#capture)
* [Symfony integration](#symfony-integration)

## Capture

**Note** that Authorization is done via Capture request.
First, the payment is authorized via Capture request and 
then it is automatically captured via Notify request.

```php
use Payum\Core\Request\Capture;

$payment = [];
$payment['method'] = 'directdebit_oneoff';
$payment['amount'] = '20';
$payment['currency'] = 'EUR';
$payment['sepaIban'] = SensitiveValue::ensureSensitive('DE69103442341234545489');
$payment['sepaHolder'] = SensitiveValue::ensureSensitive('Doe');

$payum
    ->getGateway('mollie')
    ->execute(new Capture($payment));
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
        $details['method'] = 'directdebit_oneoff';
        $details['currency'] = 'EUR';
        $details['amount'] = 5;
        $details['sepaIban'] = SensitiveValue::ensureSensitive('DE69103442341234545489');
        $details['sepaHolder'] = SensitiveValue::ensureSensitive('Doe');

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

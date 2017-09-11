# Credit Card

One-off Credit Card payments are supported.

* [Capture](#capture)
* [Create Mandate](#create-mandate)

## Capture

```php
use Payum\Core\Request\Capture;

$payment = [];
$payment['method'] = 'creditcard';

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
        $details['method'] = 'creditcard';
        $details['currency'] = 'EUR';
        $details['amount'] = 5;

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
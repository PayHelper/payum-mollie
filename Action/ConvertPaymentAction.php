<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\BankAccountInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetCurrency;
use Payum\Core\Security\SensitiveValue;

class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $this->gateway->execute($currency = new GetCurrency($payment->getCurrencyCode()));

        $divisor = 10 ** $currency->exp;
        $details = $payment->getDetails();

        $details['amount'] = (float) $payment->getTotalAmount() / $divisor;
        $details['currency'] = $payment->getCurrencyCode();
        $details['description'] = $payment->getDescription();

        /** @var BankAccountInterface $bankAccount */
        if (null !== ($bankAccount = $payment->getBankAccount())) {
            $details['bankAccount'] = SensitiveValue::ensureSensitive([
                'iban' => $bankAccount->getIban(),
                'holder' => $bankAccount->getHolder(),
            ]);
        }

        $request->setResult($details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
        ;
    }
}

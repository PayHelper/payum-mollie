<?php

namespace Sourcefabric\Payum\Mollie\Action;

use LogicException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\ObtainCreditCard;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Sourcefabric\Payum\Mollie\Request\Api\CreateCapture;
use Sourcefabric\Payum\Mollie\Request\Api\CreateRecurringSubscription;
use Sourcefabric\Payum\Mollie\Request\Api\CreateSepaMandate;

class CaptureAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var GenericTokenFactoryInterface
     */
    protected $tokenFactory;

    /**
     * @param GenericTokenFactoryInterface $genericTokenFactory
     */
    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null)
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (isset($model['payment']) && in_array($model['payment']['status'], ['cancelled', 'pending', 'failed', 'refunded', 'open'], true)) {
            // payload will be send to the notify url
            dump($model['notifyUrl']);
            die;

            // if open mark as pending or authorized
            return;
        }

        //        if (false == $model['_completeCaptureRequired']) {
        //            if (false == $model->validateNotEmpty(array('card'), false) && false == $model->validateNotEmpty(array('cardReference'), false)) {
        //                try {
        //                    $obtainCreditCard = new ObtainCreditCard($request->getFirstModel(), $request->getModel());
        //                    $this->gateway->execute($obtainCreditCard);
        //                    $card = $obtainCreditCard->obtain();
        //                    if ($card->getToken()) {
        //                        $model['cardReference'] = $card->getToken();
        //                    } else {
        //                        $model['card'] = SensitiveValue::ensureSensitive(array(
        //                                    'number' => $card->getNumber(),
        //                                    'cvv' => $card->getSecurityCode(),
        //                                    'expiryMonth' => $card->getExpireAt()->format('m'),
        //                                    'expiryYear' => $card->getExpireAt()->format('y'),
        //                                    'firstName' => $card->getHolder(),
        //                                    'lastName' => '',
        //                                ));
        //                    }
        //                } catch (RequestNotSupportedException $e) {
        //                    throw new LogicException('Credit card details has to be set explicitly or there has to be an action that supports ObtainCreditCard request.');
        //                }
        //            }
        //        }

        if (false == $model['returnUrl'] && $request->getToken()) {
            $model['returnUrl'] = $request->getToken()->getTargetUrl();
        }

        if (false == $model['cancelUrl'] && $request->getToken()) {
            $model['cancelUrl'] = $request->getToken()->getTargetUrl().'?cancelled=1';
        }

        if (empty($model['notifyUrl']) && $request->getToken() && $this->tokenFactory) {
            $notifyToken = $this->tokenFactory->createNotifyToken(
                $request->getToken()->getGatewayName(),
                $request->getToken()->getDetails()
            );

            $model['notifyUrl'] = $notifyToken->getTargetUrl();
        }

        if (false == $model['clientIp']) {
            $this->gateway->execute($httpRequest = new GetHttpRequest());
            $model['clientIp'] = $httpRequest->clientIp;
        }

        if (\Mollie_API_Object_Method::DIRECTDEBIT === $model['method']) {
            $this->gateway->execute(new CreateSepaMandate($model));
            $this->gateway->execute(new CreateRecurringSubscription($model));
        }

        if (\Mollie_API_Object_Method::CREDITCARD === $model['method']) {
            $this->gateway->execute(new CreateCapture($model));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

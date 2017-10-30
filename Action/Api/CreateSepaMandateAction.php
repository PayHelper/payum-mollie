<?php

declare(strict_types=1);

namespace PayHelper\Payum\Mollie\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;
use PayHelper\Payum\Mollie\Request\Api\CreateCustomer;
use PayHelper\Payum\Mollie\Request\Api\CreateSepaMandate;

class CreateSepaMandateAction extends BaseApiAwareAction
{
    /**
     * @var string
     */
    private $templateName;

    /**
     * @param string $templateName
     */
    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        // process confirm form if submitted
        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if ('POST' === $httpRequest->method) {
            $postParams = [];
            parse_str($httpRequest->content, $postParams);

            if (array_key_exists('mandate_id', $postParams) && null !== $postParams['mandate_id'] && $model['mandate']['id'] === $postParams['mandate_id']) {
                // mandate has been confirmed by the user

                return;
            }
        }

        $this->gateway->execute(new CreateCustomer($model));
        $model->validateNotEmpty(['sepaIban', 'sepaHolder', 'customer']);

        $response = $this->api->customers_mandates->withParentId($model['customer']['id'])->create([
            'method' => $model['method'],
            'consumerAccount' => $model['sepaIban']->get(),
            'consumerName' => $model['sepaHolder']->get(),
        ]);

        $mandate = ArrayObject::ensureArrayObject($response);

        if (in_array($response->status, ['pending', 'valid'], true)) {
            $response->mandateStatus = 'pending';
        }

        $model->replace(['mandate' => (array) $mandate]);

        if ($response->isValid()) {
            if ('pending' === $model['mandate']['mandateStatus']) {
                // mandate is newly created (i.e. pending) so force user to confirm it
                $this->gateway->execute($renderTemplate = new RenderTemplate($this->templateName, [
                    'model' => $model['mandate'],
                    'actionUrl' => $request->getToken() ? $request->getToken()->getTargetUrl() : null,
                    'cancelUrl' => $model['cancelUrl'],
                ]));

                throw new HttpResponse($renderTemplate->getResult());
            }
        }

        throw new LogicException('Mandate is invalid.');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CreateSepaMandate &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}

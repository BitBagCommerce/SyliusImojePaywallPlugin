<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Action;

use BitBag\SyliusImojePlugin\Api\ImojeApi;
use BitBag\SyliusImojePlugin\Resolver\SignatureResolverInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    private ?Request $request;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SignatureResolverInterface $signatureResolver,
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->apiClass = ImojeApi::class;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if (null == $this->request) {
            throw new \Exception('Request is empty');
        }

        /** @var string $content */
        $content = $this->request->getContent();
        $notificationData = json_decode($content, true);
        $transactionData = $notificationData['transaction'];

        $model = $request->getModel();
        $model['statusImoje'] = $transactionData['status'];

        $request->setModel($model);
    }

    public function supports($request): bool
    {
        if (null == $this->request) {
            return false;
        }

        return
            $request instanceof Notify &&
            $request->getModel() instanceof ArrayObject &&
            $this->signatureResolver->verifySignature($this->request, $this->api->getServiceKey());
    }
}

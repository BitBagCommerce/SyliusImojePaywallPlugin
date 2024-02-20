<?php

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

    private Request $request;

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

        $notificationData = json_decode($this->request->getContent(), true);
        $transactionData = $notificationData['transaction'];

        $model = $request->getModel();
        $model['statusImoje'] = $transactionData['status'];

        $request->setModel($model);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Notify
            && $request->getModel() instanceof ArrayObject
            && $this->signatureResolver->verifySignature($this->request, $this->api->getServiceKey());
    }
}

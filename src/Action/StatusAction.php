<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Action;

use ArrayAccess;
use BitBag\SyliusImojePlugin\Api\ImojeApiInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

final class StatusAction implements ActionInterface
{
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();
        $status = $model['statusImoje'] ?? null;
        $paymentId = $model['paymentId'] ?? null;

        if (($status === null || ImojeApiInterface::NEW_STATUS === $status) && null !== $paymentId) {
            $request->markNew();

            return;
        }

        if (ImojeApiInterface::PENDING_STATUS === $status) {
            $request->markPending();

            return;
        }

        if (ImojeApiInterface::CANCELLED_STATUS === $status) {
            $request->markCanceled();

            $model['tokenHash'] = '';
            $request->setModel($model);

            return;
        }

        if (ImojeApiInterface::REJECTED_STATUS === $status) {
            $request->markFailed();

            $model['tokenHash'] = '';
            $request->setModel($model);

            return;
        }

        if (ImojeApiInterface::SETTLED_STATUS === $status) {
            $request->markCaptured();

            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return $request instanceof GetStatusInterface &&
            $request->getModel() instanceof ArrayAccess
            ;
    }
}

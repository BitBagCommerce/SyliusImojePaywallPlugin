<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use Sylius\Bundle\PayumBundle\Model\PaymentSecurityTokenInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class PaymentTokenProvider implements PaymentTokenProviderInterface
{
    public function __construct(
        private readonly RepositoryInterface $orderRepository,
        private readonly RepositoryInterface $paymentTokenRepository,
    ) {}

    public function provideToken(Request $request): ?PaymentSecurityTokenInterface
    {
        $content = $request->getContent();
        $content = json_decode($content, true);

        $transactionData = $content['transaction'];

        $order = $this->getOrder($transactionData);

        foreach ($order->getPayments() as $payment) {
            $model = $payment->getDetails();

            $tokenHash = $model['tokenHash'] ?? null;

            if (
                null !== $tokenHash
                && $payment->getState() !== PaymentInterface::STATE_CANCELLED
                && $payment->getState() !== PaymentInterface::STATE_FAILED
            ) {
                return $this->getToken($tokenHash);
            }
        }

        return null;
    }

    private function getOrder(array $transactionData): ?OrderInterface
    {
        return $this->orderRepository->findOneBy(['number' => $transactionData['orderId']]);
    }

    private function getToken(string $hash): ?PaymentSecurityTokenInterface
    {
        return $this->paymentTokenRepository->findOneBy(['hash' => $hash]);
    }
}

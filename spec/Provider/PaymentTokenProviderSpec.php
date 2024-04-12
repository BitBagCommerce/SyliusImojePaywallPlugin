<?php

namespace spec\BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Provider\PaymentTokenProvider;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\PayumBundle\Model\PaymentSecurityTokenInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentTokenProviderSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $orderRepository,
        RepositoryInterface $paymentTokenRepository,
    ): void {
        $this->beConstructedWith($orderRepository, $paymentTokenRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PaymentTokenProvider::class);
    }

    public function it_should_return_token_correctly(
        Request $request,
        OrderInterface $order,
        PaymentSecurityTokenInterface $token,
        PaymentInterface $payment,
        RepositoryInterface $orderRepository,
        RepositoryInterface $paymentTokenRepository,
    ): void {
        $orderNumber = 500;
        $tokenHash = '3423423453fsxzc';

        $transactionData = [
            'transaction' => [
                'orderId' => $orderNumber,
                'tokenHash' => $tokenHash,
            ]
        ];

        $request->getContent()
            ->willReturn(json_encode($transactionData));

        $orderRepository->findOneBy(['number' => $orderNumber])
            ->willReturn($order);

        $order->getPayments()
            ->willReturn(new ArrayCollection([
                $payment->getWrappedObject()
            ]));

        $payment->getState()
            ->willReturn(PaymentInterface::STATE_NEW);

        $payment->getDetails()
            ->shouldBeCalled()
            ->willReturn(['tokenHash' => $tokenHash]);

        $paymentTokenRepository->findOneBy(['hash' => $tokenHash])
            ->willReturn($token)
            ->shouldBeCalled();

        $this->provideToken($request)->shouldReturn($token);
    }

    public function it_should_return_null_if_paymentTokenRepository_is_not_called(
        Request $request,
        OrderInterface $order,
        PaymentInterface $payment,
        RepositoryInterface $orderRepository,
    ): void {
        $orderNumber = 500;
        $tokenHash = '3423423453fsxzc';

        $transactionData = [
            'transaction' => [
                'orderId' => $orderNumber,
                'tokenHash' => $tokenHash,
            ]
        ];

        $request->getContent()
            ->willReturn(json_encode($transactionData));

        $orderRepository->findOneBy(['number' => $orderNumber])
            ->willReturn($order);

        $order->getPayments()
            ->willReturn(new ArrayCollection([
                $payment->getWrappedObject()
            ]));

        $payment->getState()
            ->willReturn(PaymentInterface::STATE_CANCELLED);

        $payment->getDetails()
            ->shouldBeCalled()
            ->willReturn(['tokenHash' => $tokenHash]);


        $this->provideToken($request)->shouldReturn(null);

    }
}

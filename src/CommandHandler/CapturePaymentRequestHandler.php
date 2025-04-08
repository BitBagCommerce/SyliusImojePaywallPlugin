<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\CommandHandler;

use BitBag\SyliusImojePlugin\Command\CapturePaymentRequest;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CapturePaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private StateMachineInterface $stateMachine,
    ) {}

    public function __invoke(CapturePaymentRequest $capturePaymentRequest): void
    {
        // Retrieve the current PaymentRequest based on the hash provided in the CapturePaymentRequest command
        $paymentRequest = $this->paymentRequestProvider->provide($capturePaymentRequest);

        // Custom capture logic for the payment provider would go here.
        // Example: communicating with the payment gateway API to capture funds.

        // Mark the PaymentRequest as complete|process|fail|cancel.
        $this->stateMachine->apply(
            $paymentRequest,
            PaymentRequestTransitions::GRAPH,
            PaymentRequestTransitions::TRANSITION_COMPLETE
        );
    }
}

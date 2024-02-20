<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use Sylius\Bundle\PayumBundle\Model\PaymentSecurityTokenInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaymentTokenProviderInterface
{
    public function provideToken(Request $request): ?PaymentSecurityTokenInterface;
}

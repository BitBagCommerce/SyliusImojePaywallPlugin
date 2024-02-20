<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface SignatureResolverInterface
{
    public function createSignature(array $fields, string $serviceKey): string;

    public function verifySignature(Request $request, string $serviceKey): bool;
}

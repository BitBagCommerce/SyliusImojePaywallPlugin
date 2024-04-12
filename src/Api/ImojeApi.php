<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Api;

class ImojeApi implements ImojeApiInterface
{
    public function __construct(
        private readonly string $environment,
        private readonly string $merchantId,
        private readonly string $serviceId,
        private readonly string $serviceKey,
        private readonly string $authorizationToken,
    ) {
    }

    public function getApiUrl(): string
    {
        return $this->environment === 'production' ? self::PRODUCTION_PAYWALL_URL : self::SANDBOX_PAYWALL_URL;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getServiceKey(): string
    {
        return $this->serviceKey;
    }

    public function getAuthorizationToken(): string
    {
        return $this->authorizationToken;
    }
}

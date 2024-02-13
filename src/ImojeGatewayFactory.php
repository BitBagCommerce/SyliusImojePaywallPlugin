<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin;

use BitBag\SyliusImojePlugin\Bridge\ImojeBridgeInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class ImojeGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults(
            [
                'payum.factory_name' => 'imoje',
                'payum.factory_title' => 'Imoje'
            ]
        );

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => ImojeBridgeInterface::SANDBOX_ENVIRONMENT,
                'merchant_id' => '',
                'service_id' => '',
                'service_key' => '',
                'authorization_token' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'merchant_id', 'service_id', 'service_key', 'authorization_token'];

            $config['payum.api'] = static function (ArrayObject $config): array {
                $config->validateNotEmpty($config['payum.required_options']);

                return [
                    'environment' => $config['environment'],
                    'merchant_id' => $config['merchant_id'],
                    'service_id' => $config['service_id'],
                    'service_key' => $config['service_key'],
                    'authorization_token' => $config['authorization_token'],
                ];
            };
        }
    }
}

<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin;

use BitBag\SyliusImojePlugin\Api\ImojeApi;
use BitBag\SyliusImojePlugin\Api\ImojeApiInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class ImojeGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults(
            [
                'payum.factory_name' => 'imoje',
                'payum.factory_title' => 'Imoje',
            ],
        );

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => ImojeApiInterface::SANDBOX_ENVIRONMENT,
                'merchant_id' => '',
                'service_id' => '',
                'service_key' => '',
                'authorization_token' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'merchant_id', 'service_id', 'service_key', 'authorization_token'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new ImojeApi(
                    $config['environment'],
                    $config['merchant_id'],
                    $config['service_id'],
                    $config['service_key'],
                    $config['authorization_token'],
                );
            };
        }
    }
}

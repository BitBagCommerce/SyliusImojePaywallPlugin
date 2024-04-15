<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusImojePlugin;

use BitBag\SyliusImojePlugin\ImojeGatewayFactory;
use Payum\Core\GatewayFactoryInterface;
use PhpSpec\ObjectBehavior;

class ImojeGatewayFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ImojeGatewayFactory::class);
    }

    function it_implements_imoje_gateway_factory_interface(): void{
        $this->shouldHaveType(GatewayFactoryInterface::class);
    }


}


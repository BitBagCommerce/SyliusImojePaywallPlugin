<?php

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


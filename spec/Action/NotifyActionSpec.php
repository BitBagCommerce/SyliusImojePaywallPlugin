<?php

namespace spec\BitBag\SyliusImojePlugin\Action;

use BitBag\SyliusImojePlugin\Api\ImojeApi;
use BitBag\SyliusImojePlugin\Resolver\SignatureResolverInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;


class NotifyActionSpec extends ObjectBehavior
{
    public function let(
        RequestStack $requestStack,
        SignatureResolverInterface $signatureResolver,
    ): void {
        $this->beConstructedWith(
            $requestStack,
            $signatureResolver
        );
    }
    public function it_should_return_true_when_request_and_request_data_is_valid(
        Notify $request,
        ArrayObject $arrayObject,
        SignatureResolverInterface $signatureResolver,
        ImojeApi $api,
        Request $httpRequest,
        RequestStack $requestStack,
    ): void {
        $request->getModel()->willReturn($arrayObject);
        $requestStack->getCurrentRequest()
            ->willReturn($httpRequest);
        $api->getServiceKey()->willReturn('1234sdcsdfxz');

        $signatureResolver->verifySignature($httpRequest, '1234sdcsdfxz')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->setApi($api);
        $this->supports($request)->shouldReturn(true);
    }

    public function it_should_return_false_when_request_and_signature_are_invalid(
        Notify $request,
        ArrayObject $arrayObject,
        SignatureResolverInterface $signatureResolver,
        ImojeApi $api,
        Request $httpRequest,
        RequestStack $requestStack,
    ): void {
        $request->getModel()->willReturn($arrayObject);
        $requestStack->getCurrentRequest()->willReturn($httpRequest);
        $api->getServiceKey()->willReturn('1234sdcsdfxz');

        $signatureResolver->verifySignature($httpRequest, '1234sdcsdfxz')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->setApi($api);
        $this->supports($request)->shouldReturn(false);
    }

    public function it_should_return_false_when_request_is_empty(
        Notify $request,
        ImojeApi $api,
        RequestStack $requestStack,
        Request $httpRequest,
    ): void {
        $request->getModel()->willReturn(null);
        $requestStack->getCurrentRequest()->willReturn($httpRequest);
        $api->getServiceKey()->willReturn(null);

        $this->supports($request)->shouldReturn(false);
    }

    public function it_should_return_false_when_request_is_invalid_and(
        Notify $request,
        RequestStack $requestStack,
        Request $httpRequest,
    ): void {
        $request->getModel()->willReturn(null);
        $requestStack->getCurrentRequest()->willReturn($httpRequest);

        $this->supports($httpRequest)->shouldReturn(false);
    }

    public function it_sets_model_status_from_notification_data(
        Notify $request,
        ArrayObject $arrayObject,
        SignatureResolverInterface $signatureResolver,
        ImojeApi $api,
        Request $httpRequest,
        RequestStack $requestStack,
    ): void {
        $requestStack->getCurrentRequest()->willReturn($httpRequest);
        $api->getServiceKey()->willReturn('1234sdcsdfxz');

        $signatureResolver->verifySignature($httpRequest, '1234sdcsdfxz')
            ->willReturn(true)
            ->shouldBeCalled();

        $notificationData = ['transaction' => ['status' => 'new','paymentId' => 1, 'tokenHash'=>'1234sdcsdfxz']];
        $jsonNotificationData = json_encode($notificationData);
        $httpRequest->getContent()->willReturn($jsonNotificationData);

        $request->getModel()->willReturn(new ArrayObject([
            'status' => 'new',
            'paymentId' => 1,
            'tokenHash'=>'1234sdcsdfxz'
        ]));

       $request->setModel(new ArrayObject([
            'status' => 'new',
            'paymentId' => 1,
            'tokenHash'=>'1234sdcsdfxz',
            'statusImoje' => 'new'
       ]))->willReturn($arrayObject)->shouldBeCalled();

        $this->setApi($api);
        $this->execute($request);
    }

}

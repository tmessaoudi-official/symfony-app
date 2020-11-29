<?php

namespace App\Kernel\EventListener\LexikJWTAuthentication;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class OnJWTCreated
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $payload['ip'] = $request->getClientIp();

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}

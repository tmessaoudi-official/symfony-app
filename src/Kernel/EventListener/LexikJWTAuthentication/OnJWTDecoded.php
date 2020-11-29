<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

namespace App\Kernel\EventListener\LexikJWTAuthentication;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OnJWTDecoded
{
    protected RequestStack $requestStack;
    protected AdapterInterface $cache;

    public function __construct(RequestStack $requestStack, AdapterInterface $cache)
    {
        $this->requestStack = $requestStack;
        $this->cache = $cache;
    }

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();

        $request = $this->requestStack->getCurrentRequest();

        $token = preg_replace('/^Bearer /', '', $request->headers->get('authorization'));

        if (empty(($token))) {
            $token = $request->query->get('token');
        }

        if (empty($token)) {
            $event->markAsInvalid();

            return;
        }

        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();

            return;
        }

        if (!isset($payload['user-agent']) || $payload['user-agent'] !== $request->headers->get('user-agent')) {
            $event->markAsInvalid();

            return;
        }


        $item = $this->cache->getItem($token);
        if ($item->isHit()) {
            $event->markAsInvalid();
        }
    }
}

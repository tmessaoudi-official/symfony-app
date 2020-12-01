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
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\ChainTokenExtractor;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;

class OnJWTDecoded
{
    protected RequestStack $requestStack;
    protected CacheInterface $appInvalidedTokens;
    protected ChainTokenExtractor $chainTokenExtractor;

    public function __construct(RequestStack $requestStack, CacheInterface $appInvalidedTokens)
    {
        $this->requestStack = $requestStack;
        $this->appInvalidedTokens = $appInvalidedTokens;
    }

    public function setChainTokenExtractor(ChainTokenExtractor $chainTokenExtractor)
    {
        $this->chainTokenExtractor = $chainTokenExtractor;
    }

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();

        $request = $this->requestStack->getCurrentRequest();

        $token = $this->chainTokenExtractor->extract($request);

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

        $item = $this->appInvalidedTokens->getItem($token);
        if ($item->isHit()) {
            $event->markAsInvalid();
        }
    }
}

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

namespace App\Service\Api\Security\Auth;

use App\Manager\Api\Security\Auth\LogoutManager;
use DateInterval;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use ReflectionClass;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Contracts\Cache\CacheInterface;

class LogoutService
{
    protected LogoutManager $logoutManager;
    protected CacheInterface $appInvalidedTokens;
    protected string $jWTTokenTTL;

    public function __construct(LogoutManager $logoutManager, CacheInterface $appInvalidedTokens, string $jWTTokenTTL)
    {
        $this->logoutManager = $logoutManager;
        $this->appInvalidedTokens = $appInvalidedTokens;
        $this->jWTTokenTTL = $jWTTokenTTL;
    }

    public function __invoke(TokenInterface $JWTUserToken): void
    {
        $this->logoutManager->__invoke($JWTUserToken->getUser());

        $this->blacklistToken($this->getTokenString($JWTUserToken));
    }

    public function blacklistToken(string $token): void
    {
        /**
         * @var CacheItem $item
         */
        $item = $this->appInvalidedTokens->getItem($token);
        $expiresAt = (new DateTime())->add(new DateInterval('PT'.$this->jWTTokenTTL.'S'));
        $item->expiresAfter((int) ($this->jWTTokenTTL));
        $item->expiresAt($expiresAt);
        $item->set([
            'token' => $token,
            'expiresAfter' => $this->jWTTokenTTL,
            'expiresAt' => $expiresAt->getTimestamp(),
            'invalidatedAt' => (new DateTime())->getTimestamp(),
        ]);
        $this->appInvalidedTokens->save($item);
    }

    protected function getTokenString(TokenInterface $JWTUserToken)
    {
        $reflection = new ReflectionClass(\get_class($JWTUserToken));
        $rawToken = $reflection->getProperty('rawToken');
        $rawToken->setAccessible(true);

        return $rawToken->getValue($JWTUserToken);
    }
}

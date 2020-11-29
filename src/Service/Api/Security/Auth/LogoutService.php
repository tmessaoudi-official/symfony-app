<?php

namespace App\Service\Api\Security\Auth;

use App\Manager\Api\Security\Auth\LogoutManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use DateTime;
use DateInterval;
use ReflectionClass;

class LogoutService
{
    protected LogoutManager $logoutManager;
    protected AdapterInterface $cache;
    protected string $jWTTokenTTL;

    public function __construct(LogoutManager $logoutManager, AdapterInterface $cache, string $jWTTokenTTL)
    {
        $this->logoutManager = $logoutManager;
        $this->cache = $cache;
        $this->jWTTokenTTL = $jWTTokenTTL;
    }

    public function __invoke(TokenInterface|JWTUserToken $JWTUserToken)
    {
        $this->logoutManager->__invoke($JWTUserToken->getUser());
        /**
         * @var $item CacheItem
         */
        $reflection = new ReflectionClass(get_class($JWTUserToken));
        $rawToken = $reflection->getProperty('rawToken');
        $rawToken->setAccessible(true);
        $token = $rawToken->getValue($JWTUserToken);
        $item = $this->cache->getItem($token);
        $expireAt = (new DateTime())->add(new DateInterval('PT'.$this->jWTTokenTTL.'S'));
        $item->expiresAfter(intval($this->jWTTokenTTL));
        $item->expiresAt($expireAt);
        $item->set([
            'token' => $token,
            'expiresAfter' => $this->jWTTokenTTL,
            'expiresAt' => $expireAt->getTimestamp(),
            'invalidatedAt' => (new DateTime())->getTimestamp(),
        ]);
        $this->cache->save($item);
    }
}

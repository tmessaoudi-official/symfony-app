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

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Service;

use App\Override\Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use App\Service\Api\Security\Auth\LogoutService;
use Gesdinet\JWTRefreshTokenBundle\Event\RefreshEvent;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Security\Authenticator\RefreshTokenAuthenticator;
use Gesdinet\JWTRefreshTokenBundle\Security\Provider\RefreshTokenProvider;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class RefreshToken
{
    protected RefreshTokenAuthenticator $authenticator;

    protected RefreshTokenProvider $provider;

    protected AuthenticationSuccessHandlerInterface $successHandler;

    protected AuthenticationFailureHandlerInterface $failureHandler;

    protected RefreshTokenManagerInterface $refreshTokenManager;

    protected int $ttl;

    protected string $providerKey;

    protected bool $ttlUpdate;

    protected EventDispatcherInterface $eventDispatcher;

    protected LogoutService $logoutService;

    public function __construct(
        RefreshTokenAuthenticator $authenticator,
        RefreshTokenProvider $provider,
        AuthenticationSuccessHandlerInterface $successHandler,
        AuthenticationFailureHandlerInterface $failureHandler,
        RefreshTokenManagerInterface $refreshTokenManager,
        int $ttl,
        string $providerKey,
        bool $ttlUpdate,
        EventDispatcherInterface $eventDispatcher,
        LogoutService $logoutService
    ) {
        $this->authenticator = $authenticator;
        $this->provider = $provider;
        $this->successHandler = $successHandler;
        $this->failureHandler = $failureHandler;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->ttl = $ttl;
        $this->providerKey = $providerKey;
        $this->ttlUpdate = $ttlUpdate;
        $this->eventDispatcher = $eventDispatcher;
        $this->logoutService = $logoutService;
    }

    /**
     * Refresh token.
     *
     * @throws InvalidArgumentException
     * @throws AuthenticationException
     */
    public function refresh(Request $request): Response
    {
        try {
            $user = $this->authenticator->getUser(
                $this->authenticator->getCredentials($request),
                $this->provider
            );

            $postAuthenticationToken = $this->authenticator->createAuthenticatedToken($user, $this->providerKey);
        } catch (AuthenticationException $e) {
            return $this->failureHandler->onAuthenticationFailure($request, $e);
        }
        $jwtToken = @json_decode($request->getContent(), true)['token'];
        if (!$jwtToken) {
            $jwtToken = $request->query->get('token');
        }
        $credentials = $this->authenticator->getCredentials($request);
        $refreshToken = $this->refreshTokenManager->get($credentials['token']);

        if (null === $refreshToken || !$refreshToken->isValid() || $refreshToken->getIp() !== $request->getClientIp() || $refreshToken->getUserAgent() !== $request->headers->get('user-agent') || empty($jwtToken)) {
            return $this->failureHandler->onAuthenticationFailure(
                $request,
                new AuthenticationException(
                    sprintf('Refresh token "%s" is invalid.', $refreshToken)
                )
            );
        }

        $this->logoutService->blacklistToken($jwtToken);

        if ($this->ttlUpdate) {
            $expirationDate = new \DateTime();
            $expirationDate->modify(sprintf('+%d seconds', $this->ttl));
            $refreshToken->setValid($expirationDate);

            $this->refreshTokenManager->save($refreshToken);
        }

        if ($this->eventDispatcher instanceof ContractsEventDispatcherInterface) {
            $this->eventDispatcher->dispatch(new RefreshEvent($refreshToken, $postAuthenticationToken), 'gesdinet.refresh_token');
        } else {
            $this->eventDispatcher->dispatch('gesdinet.refresh_token', new RefreshEvent($refreshToken, $postAuthenticationToken));
        }

        $this->refreshTokenManager->deleteBy(['username' => $user->getEmail(), 'ip' => $request->getClientIp()]);

        return $this->successHandler->onAuthenticationSuccess($request, $postAuthenticationToken);
    }
}

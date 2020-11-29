<?php

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Service;

use App\Override\Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use Gesdinet\JWTRefreshTokenBundle\Event\RefreshEvent;
use Gesdinet\JWTRefreshTokenBundle\Security\Authenticator\RefreshTokenAuthenticator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Security\Provider\RefreshTokenProvider;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class RefreshToken
{
    /**
     * @var RefreshTokenAuthenticator
     */
    protected $authenticator;

    /**
     * @var RefreshTokenProvider
     */
    protected $provider;

    /**
     * @var AuthenticationSuccessHandlerInterface
     */
    protected $successHandler;

    /**
     * @var AuthenticationFailureHandlerInterface
     */
    protected $failureHandler;

    protected RefreshTokenManagerInterface | RefreshTokenManager $refreshTokenManager;

    /**
     * @var int
     */
    protected $ttl;

    /**
     * @var string
     */
    protected $providerKey;

    /**
     * @var bool
     */
    protected $ttlUpdate;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * RefreshToken constructor.
     *
     * @param RefreshTokenAuthenticator             $authenticator
     * @param RefreshTokenProvider                  $provider
     * @param AuthenticationSuccessHandlerInterface $successHandler
     * @param AuthenticationFailureHandlerInterface $failureHandler
     * @param RefreshTokenManagerInterface          $refreshTokenManager
     * @param int                                   $ttl
     * @param string                                $providerKey
     * @param bool                                  $ttlUpdate
     * @param EventDispatcherInterface              $eventDispatcher
     */
    public function __construct(
        RefreshTokenAuthenticator $authenticator,
        RefreshTokenProvider $provider,
        AuthenticationSuccessHandlerInterface $successHandler,
        AuthenticationFailureHandlerInterface $failureHandler,
        RefreshTokenManagerInterface $refreshTokenManager,
        $ttl,
        $providerKey,
        $ttlUpdate,
        EventDispatcherInterface $eventDispatcher
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
    }

    /**
     * Refresh token.
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws AuthenticationException
     */
    public function refresh(Request $request)
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

        $credentials = $this->authenticator->getCredentials($request);
        $refreshToken = $this->refreshTokenManager->get($credentials['token']);

        if (null === $refreshToken || !$refreshToken->isValid() || $refreshToken->getIp() !== $request->getClientIp()) {
            return $this->failureHandler->onAuthenticationFailure(
                $request,
                new AuthenticationException(
                    sprintf('Refresh token "%s" is invalid.', $refreshToken)
                )
            );
        }

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

        $this->refreshTokenManager->deleteByUser($user, ['ip' => $request->getClientIp()]);

        return $this->successHandler->onAuthenticationSuccess($request, $postAuthenticationToken);
    }
}

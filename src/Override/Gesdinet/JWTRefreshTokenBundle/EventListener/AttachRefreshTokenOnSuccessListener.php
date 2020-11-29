<?php

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\EventListener;

use App\Override\Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Request\RequestRefreshToken;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessListener as OriginalAttachRefreshTokenOnSuccessListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

class AttachRefreshTokenOnSuccessListener extends OriginalAttachRefreshTokenOnSuccessListener
{
    protected OriginalAttachRefreshTokenOnSuccessListener $decorated;

    public function __construct(
        RefreshTokenManagerInterface $refreshTokenManager,
        $ttl,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        $userIdentityField,
        $tokenParameterName,
        $singleUse
    ) {
        parent::__construct($refreshTokenManager, $ttl, $validator, $requestStack, $userIdentityField, $tokenParameterName, $singleUse);
    }

    public function setDecorationInner(OriginalAttachRefreshTokenOnSuccessListener $decorated): void
    {
        $this->decorated = $decorated;
    }

    protected function generateRefreshToken(AuthenticationSuccessEvent $event): ?array
    {
        $data = $event->getData();
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();

        if (!$user instanceof UserInterface) {
            return null;
        }

        $refreshTokenString = RequestRefreshToken::getRefreshToken($request, $this->tokenParameterName);

        if ($refreshTokenString && true === $this->singleUse) {
            $refreshToken = $this->refreshTokenManager->get($refreshTokenString);
            $refreshTokenString = null;

            if ($refreshToken instanceof RefreshTokenInterface) {
                $this->refreshTokenManager->delete($refreshToken);
            }
        }

        if ($refreshTokenString) {
            $data[$this->tokenParameterName] = $refreshTokenString;
        } else {
            $datetime = new DateTime();
            $datetime->modify('+'.$this->ttl.' seconds');

            /**
             * @var RefreshToken
             */
            $refreshToken = $this->refreshTokenManager->create();

            $accessor = new PropertyAccessor();
            $userIdentityFieldValue = $accessor->getValue($user, $this->userIdentityField);

            $refreshToken->setUsername($userIdentityFieldValue);
            $refreshToken->setRefreshToken();
            $refreshToken->setValid($datetime);
            $refreshToken->setIp($request->getClientIp());

            $valid = false;
            while (false === $valid) {
                $valid = true;
                $errors = $this->validator->validate($refreshToken);
                if ($errors->count() > 0) {
                    foreach ($errors as $error) {
                        if ('refreshToken' === $error->getPropertyPath()) {
                            $valid = false;
                            $refreshToken->setRefreshToken();
                        }
                    }
                }
            }

            $this->refreshTokenManager->save($refreshToken);
            $data[$this->tokenParameterName] = $refreshToken->getRefreshToken();
        }
        return $data;
    }

    public function attachRefreshToken(AuthenticationSuccessEvent $event)
    {
        $data = $this->generateRefreshToken($event);
        if ($data) {
            $event->setData($data);
        }
    }
}

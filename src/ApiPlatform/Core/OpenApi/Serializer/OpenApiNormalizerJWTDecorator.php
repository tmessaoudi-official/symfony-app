<?php

declare(strict_types=1);

namespace App\ApiPlatform\Core\OpenApi\Serializer;

use ApiPlatform\Core\OpenApi\Serializer\OpenApiNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class OpenApiNormalizerJWTDecorator implements NormalizerInterface
{
    private OpenApiNormalizer $decorated;

    public function __construct(OpenApiNormalizer $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return $this->decorated->hasCacheableSupportsMethod();
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs['components']['schemas']['Token'] = [
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];

        $docs['components']['schemas']['Credentials'] = [
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                ],
                'password' => [
                    'type' => 'string',
                ],
            ],
        ];

        $docs['paths']['/api/auth/login'] = [
            'post' => [
                'tags' => ['Token'],
                'operationId' => 'postCredentialsItem',
                'summary' => 'Get JWT token to login.',
                'requestBody' => [
                    'description' => 'Create new JWT Token',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    Response::HTTP_OK => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $docs;
    }
}

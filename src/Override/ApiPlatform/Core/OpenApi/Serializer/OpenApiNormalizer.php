<?php

declare(strict_types=1);

namespace App\Override\ApiPlatform\Core\OpenApi\Serializer;

use ApiPlatform\Core\OpenApi\Serializer\OpenApiNormalizer as OriginalOpenApiNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class OpenApiNormalizer implements NormalizerInterface
{
    private OriginalOpenApiNormalizer $decorated;

    public function setDecorationInner(OriginalOpenApiNormalizer $decorated)
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
                'refresh_token' => [
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

        $docs['components']['schemas']['RefreshToken'] = [
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
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

        $docs['paths']['/api/auth/token/refresh'] = [
            'post' => [
                'tags' => ['RefreshToken'],
                'operationId' => 'postRefereshToken',
                'summary' => 'Refresh current JWT Token.',
                'requestBody' => [
                    'description' => 'Create new JWT Token from refresh_token',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefreshToken',
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

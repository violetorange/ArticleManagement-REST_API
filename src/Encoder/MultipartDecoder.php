<?php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        return $request->request->all() + $request->files->all();
    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}

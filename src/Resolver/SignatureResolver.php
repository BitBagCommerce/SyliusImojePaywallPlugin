<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver;

use BitBag\SyliusImojePlugin\Api\ImojeApiInterface;
use Symfony\Component\HttpFoundation\Request;

final class SignatureResolver implements SignatureResolverInterface
{
    public function createSignature(array $fields, string $serviceKey): string
    {
        ksort($fields);

        $data = array_map(function ($key, $value) {
            if (is_array($value)) {
                $value = http_build_query([$key => $value], '', '&');
                $key = '';
            }

            return $key !== '' ? "$key=$value" : $value;
        }, array_keys($fields), $fields);

        $dataString = implode('&', $data);

        return hash(ImojeApiInterface::HASHING_ALGORITHM, $dataString . $serviceKey) . ';' . ImojeApiInterface::HASHING_ALGORITHM;
    }

    public function verifySignature(Request $request, string $serviceKey): bool
    {
        $headerSignature = $request->headers->get('X-Imoje-Signature');
        $body = $request->getContent();

        $parts = [];

        if($headerSignature !== null ) {
            parse_str(str_replace([';', '='], ['&', '='], $headerSignature), $parts);
        }

        $algo = is_string($parts['alg']) ? $parts['alg'] : 'sha256';
        $ownSignature = hash($algo, $body . $serviceKey);

        return $ownSignature === $parts['signature'];
    }
}

<?php

namespace App\Serializer;

use App\Entity\UserClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserClientNormalizer implements ContextAwareNormalizerInterface
{
    private $router;
    private $normalizer;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($topic, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($topic, $format, $context);
// Here, add, edit, or delete some data:
        $data['href']['self'] = $this->router->generate('api_user_detail', [
            'id' => $topic->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        $data['href']['add'] = $this->router->generate('api_users_insert', [
            'id' => $topic->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        $data['href']['delete'] = $this->router->generate('api_users_delete', [
            'id' => $topic->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof UserClient;
    }
}
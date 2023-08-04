<?php

declare(strict_types=1);

namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use App\Entity\Intervention;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class InterventionContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(
        SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(
        Request $request,
        bool $normalization,
        ?array $extractedAttributes = null
    ): array {
        $context = $this->decorated->createFromRequest(
            $request,
            $normalization,
            $extractedAttributes
        );
        $resourceClass = $context['resource_class'] ?? null;

        if (
            $resourceClass === Intervention::class
            && isset($context['groups'])
            && $this->authorizationChecker->isGranted('ROLE_ADD_PARTICIPANTS_TO_INTERVENTION')
            && false === $normalization
        ) {
            $context['groups'][] = 'intervention:patch';
            $context['groups'][] = 'intervention:post';
        }

        return $context;
    }
}

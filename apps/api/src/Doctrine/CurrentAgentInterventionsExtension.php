<?php

declare(strict_types=1);

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class CurrentAgentInterventionsExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {

        if ($operation->getUriTemplate() === '/users/{userId}/interventions') {
            if ((int) $context['uri_variables']['userId'] !== $this->security->getUser()->getId()) {
                throw new AccessDeniedException();
            }

            $this->addWhere($queryBuilder, $this->security->getUser()->getId());
        }
    }

    private function addWhere(
        QueryBuilder $queryBuilder,
        int $userId
    ): void {
        $interventionAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(':userId MEMBER OF ' . $interventionAlias . '.participants');
        $queryBuilder->setParameter('userId', $userId);
    }
}

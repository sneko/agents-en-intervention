<?php

declare(strict_types=1);

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Group;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ActiveAgentsExtension implements QueryCollectionExtensionInterface
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

        if ($operation->getUriTemplate() === '/employers/{employerId}/active-agents') {
            $employer = $this->security->getUser()->getEmployer();
            if ((int) $context['uri_variables']['employerId'] !== $employer->getId()) {
                throw new AccessDeniedException();
            }

            $this->addWhere($queryBuilder, $employer->getId());
        }
    }

    private function addWhere(
        QueryBuilder $queryBuilder,
        int $employerId
    ): void {
        $interventionAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere($interventionAlias . '.employer = :employerId');
        $queryBuilder->setParameter('employerId', $employerId);
        $queryBuilder->andWhere($interventionAlias . '.active = true');

        $queryBuilder->join(
            $interventionAlias . '.groups',
            'g',
            Expr\Join::WITH,
            "g.slug = '" . Group::AGENT . "'"
        );
    }
}

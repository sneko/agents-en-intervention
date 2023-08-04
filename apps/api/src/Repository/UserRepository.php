<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Dépôt pour l'entité User.
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    // Méthodes magiques :

    /**
     * Le constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry the registry manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    // Méthodes :

    /**
     * Renvoie l'utilisateur et ses rôles.
     * @param string $login l'identifiant.
     * @return array l'utilisateur et ses rôles.
     */
    public function loadUserByIdentifier(string $login): ?User
    {
        $queryBuilder = $this->createQueryBuilder('user');
        $queryBuilder
            ->where("user.login = :login")
            ->setParameter("login", $login)
        ;
        $user = $queryBuilder->getQuery()->getOneOrNullResult();

        if (
            $user === null
            || $user->isActive() === false
        ) {
            return null;
        }

        $roles = $this->getUserRoles($user->getId());
        $user->setRoles($roles);

         return $user;
    }

    /**
     * Renvoie les rôles de l'utilisateur.
     * @param int $userId l'identifiant de l'utilisateur.
     * @return array les rôles de l'utilisateur.
     */
    private function getUserRoles(int $userId): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $sql = '
            select distinct
                role.name
            from
                "user"
            inner join user_group
                on "user".id = user_group.user_id
            inner join "group"
                on user_group.group_id = "group".id
            inner join group_role
                on "group".id = group_role.group_id
            inner join role
                on group_role.role_id = role.id
            where
                "user".id = :userId
            ';
        $statement = $connection->prepare($sql);
        $statement->bindValue('userId', $userId);
        $result = $statement->executeQuery();

        return $result->fetchFirstColumn();
    }
}

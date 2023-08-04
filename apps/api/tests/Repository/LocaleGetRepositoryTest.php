<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes as PA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests the locale GET repository.
 */
#[
    PA\CoversClass(UserRepository::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\Group('repositories'),
    PA\Group('repositories_user'),
    PA\Group('user')
]
final class LocaleGetRepositoryTest extends WebTestCase
{
    // Méthodes :

    private function addUser(): void
    {
        $writeRole = new Role('ROLE_ALL_WRITE', null);
        $readRole = new Role('ROLE_ALL_READ', null);

        $group1 = new Group('group1', 'group1', null);
        $group1->addRole($writeRole);
        $group1->addRole($readRole);

        $group2 = new Group('group2', 'group2', null);
        $group2->addRole($readRole);

        $employer = new Employer('employer-siren', 'employer-name');
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'user-picture',
            $employer
        );
        $user->addGroup($group1);
        $user->addGroup($group2);

        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($writeRole);
        $manager->persist($readRole);
        $manager->persist($group1);
        $manager->persist($group2);
        $manager->persist($employer);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Test que l'on puisse charger
     * un utilisateur avec son identifiant.
     */
    public function testCanLoadAUserWithItsIdentifier(): void
    {
        $this->addUser();

        /**
         * @var \App\Repository\UserRepository $localeRepository le dépôt de l'utilisateur.
         */
        $localeRepository = static::getContainer()->get(UserRepository::class);
        $foundUser = $localeRepository->loadUserByIdentifier('user-login');

        $roles = $foundUser->getRoles();

        self::assertCount(2, $roles);
        self::assertContains('ROLE_ALL_READ', $roles);
        self::assertContains('ROLE_ALL_WRITE', $roles);
    }

    /**
     * Test que l'on ne puisse pas charger
     * un utilisateur inconnu.
     */
    public function testCanLoadAUserWithAnUnknownIdentifier(): void
    {
        /**
         * @var \App\Repository\UserRepository $localeRepository le dépôt de l'utilisateur.
         */
        $localeRepository = static::getContainer()->get(UserRepository::class);
        $notFoundUser = $localeRepository->loadUserByIdentifier('not-a-user-login');

        self::assertNull($notFoundUser);
    }

    /**
     * Ajoute un utilisateur désactivé.
     */
    private function addADeactivatedUser(): void
    {
        $container = self::getContainer();

        $writeRole = new Role('ROLE_ALL_WRITE', null);
        $readRole = new Role('ROLE_ALL_READ', null);

        $adminGroup = new Group('admin', 'admin', null);
        $adminGroup->addRole($writeRole);
        $adminGroup->addRole($readRole);

        $electedGroup = new Group('user', 'user', null);
        $electedGroup->addRole($readRole);

        $employer = new Employer('employer-siren', 'employer-name');
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'user-picture',
            $employer,
            false
        );
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'user-password')
        );
        $user->addGroup($adminGroup);
        $user->addGroup($electedGroup);

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($writeRole);
        $manager->persist($readRole);
        $manager->persist($adminGroup);
        $manager->persist($electedGroup);
        $manager->persist($employer);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Test que l'on ne puisse pas s'authentifier
     * avec un compte désactivé.
     */
    public function testCanNotLoadADeactivatedUser(): void
    {
        $this->addADeactivatedUser();

        /**
         * @var \App\Repository\UserRepository $localeRepository le dépôt de l'utilisateur.
         */
        $localeRepository = static::getContainer()->get(UserRepository::class);
        $notFoundUser = $localeRepository->loadUserByIdentifier('user-login');

        self::assertNull($notFoundUser);
    }
}

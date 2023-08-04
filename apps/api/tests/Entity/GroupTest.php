<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Group.
 */
#[
    PA\CoversClass(Group::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\Group('entities'),
    PA\Group('entities_group'),
    PA\Group('group')
]
final class GroupTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertNull($group->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertSame('group-name', $group->getName());

        $group->setName('new-name');

        self::assertSame('new-name', $group->getName());
    }


    /**
     * Test que le slug soit accessible.
     */
    public function testCanGetAndSetSlug(): void
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertSame('group-slug', $group->getSlug());

        $group->setSlug('new-slug');

        self::assertSame('new-slug', $group->getSlug());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertNull($group->getDescription());

        $group->setDescription('new-description');

        self::assertSame('new-description', $group->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $group = new Group('group-name', 'group-slug', 'group-description');

        self::assertSame('group-description', $group->getDescription());

        $group->setDescription(null);

        self::assertNull($group->getDescription());
    }


    /**
     * Test que l'on puisse ajouter un utilisateur.
     * @return \App\Entity\Group le groupe.
     */
    public function testCanAddAUser(): Group
    {
        $group = new Group('group-name', 'group-slug', null);
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertEmpty($group->getUsers());

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
        $group->addUser($user);

        self::assertCount(1, $group->getUsers());

        return $group;
    }

    /**
     * Test que l'on puisse retirer un utilisateur.
     * @param \App\Entity\Group $group le groupe.
     */
    #[PA\Depends('testCanAddAUser')]
    public function testCanRemoveAUser(Group $group): void
    {
        $users = $group->getUsers();

        $group->removeUser($users[0]);

        self::assertEmpty($group->getUsers());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même utilisateur deux fois.
     */
    public function testCanNotAddTheSameUserTwice(): void
    {
        $group = new Group('group-name', 'group-slug', null);
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertEmpty($group->getUsers());

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
        $group->addUser($user);

        self::assertCount(1, $group->getUsers());

        $group->addUser($user);

        self::assertCount(1, $group->getUsers());
    }


    /**
     * Test que l'on puisse ajouter un rôle.
     * @return \App\Entity\Group le groupe.
     */
    public function testCanAddARole(): Group
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertEmpty($group->getRoles());

        $role = new Role('role-name', null);
        $group->addRole($role);

        self::assertCount(1, $group->getRoles());

        return $group;
    }

    /**
     * Test que l'on puisse retirer un rôle.
     * @param \App\Entity\Group $group le groupe.
     */
    #[PA\Depends('testCanAddARole')]
    public function testCanRemoveARole(Group $group): void
    {
        $roles = $group->getRoles();

        $group->removeRole($roles[0]);

        self::assertEmpty($group->getRoles());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même rôle deux fois.
     */
    public function testCanNotAddTheSameRoleTwice(): void
    {
        $group = new Group('group-name', 'group-slug', null);

        self::assertEmpty($group->getRoles());

        $role = new Role('role-name', null);
        $group->addRole($role);

        self::assertCount(1, $group->getRoles());

        $group->addRole($role);

        self::assertCount(1, $group->getRoles());
    }
}

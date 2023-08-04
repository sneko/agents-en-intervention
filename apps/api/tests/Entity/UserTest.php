<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Group;
use App\Entity\Employer;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité User.
 */
#[
    PA\CoversClass(User::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Employer::class),
    PA\Group('entities'),
    PA\Group('entities_user'),
    PA\Group('user')
]
final class UserTest extends TestCase
{
    // Méthodes :

    /**
     * Renvoie un substitut de l'entité Employer.
     * @return \App\Entity\Employer un substitut de l'entité Employer.
     */
    private function getMockForEmployer(): Employer
    {
        return $this->getMockBuilder(Employer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->getId());
    }


    /**
     * Test que l'identifiant de connexion soit accessible.
     */
    public function testCanGetAndSetLogin(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-login', $user->getLogin());

        $user->setLogin('new-login');

        self::assertSame('new-login', $user->getLogin());
    }


    /**
     * Test que le mot de passe soit accessible.
     */
    public function testCanGetAndPassword(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-password', $user->getPassword());

        $user->setPassword('new-password');

        self::assertSame('new-password', $user->getPassword());
    }


    /**
     * Test que le prénom soit accessible.
     */
    public function testCanGetAndSetFirstname(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-firstname', $user->getFirstname());

        $user->setFirstname('new-firstname');

        self::assertSame('new-firstname', $user->getFirstname());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetLastname(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-lastname', $user->getLastname());

        $user->setLastname('new-lastname');

        self::assertSame('new-lastname', $user->getLastname());
    }


    /**
     * Test que l'e-mail soit accessible.
     */
    public function testCanGetAndSetEmail(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->getEmail());

        $user->setEmail('new-email');

        self::assertSame('new-email', $user->getEmail());
    }

    /**
     * Test que l'e-mail puisse être nul.
     */
    public function testCanGetAndSetANullEmail(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            'user-email',
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-email', $user->getEmail());

        $user->setEmail(null);

        self::assertNull($user->getEmail());
    }


    /**
     * Test que le numéro de téléphone soit accessible.
     */
    public function testCanGetAndSetPhoneNumber(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->getPhoneNumber());

        $user->setPhoneNumber('new-phoneNumber');

        self::assertSame('new-phoneNumber', $user->getPhoneNumber());
    }

    /**
     * Test que le numéro de téléphone puisse être nul.
     */
    public function testCanGetAndSetANullPhoneNumber(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            'user-phoneNumber',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-phoneNumber', $user->getPhoneNumber());

        $user->setPhoneNumber(null);

        self::assertNull($user->getPhoneNumber());
    }


    /**
     * Test que la date de création soit accessible.
     */
    public function testCanGetAndSetCreatedAt(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $user->getCreatedAt()->format('Y-m-d H:i:s')
        );

        $user->setCreatedAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $user->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }


    /**
     * Test que la photo soit accessible.
     */
    public function testCanGetAndSetPicture(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->getPicture());

        $user->setPicture('new-picture');

        self::assertSame('new-picture', $user->getPicture());
    }

    /**
     * Test que la photo puisse être nul.
     */
    public function testCanGetAndSetANullPicture(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'user-picture',
            $this->getMockForEmployer()
        );

        self::assertSame('user-picture', $user->getPicture());

        $user->setPicture(null);

        self::assertNull($user->getPicture());
    }


    /**
     * Test que l'employeur soit accessible.
     */
    public function testCanGetAndSetEmployeur(): void
    {
        $employer = $this->getMockForEmployer();
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $employer
        );

        self::assertSame($employer, $user->getEmployer());

        $otherEmployer = $this->getMockForEmployer();
        $user->setEmployer($otherEmployer);

        self::assertSame($otherEmployer, $user->getEmployer());
    }


    /**
     * Test que le compte puisse être activé.
     */
    public function testCanBeActived(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer(),
            false
        );

        self::assertFalse($user->isActive());

        $user->setActive(true);

        self::assertTrue($user->isActive());
    }

    /**
     * Test que le compte puisse être désactivé.
     */
    public function testCanBeDeactived(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertTrue($user->isActive());

        $user->setActive(false);

        self::assertFalse($user->isActive());
    }


    /**
     * Test que la date de la dernière connexion soit accessible.
     */
    public function testCanGetAndSetConnectedAt(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->getConnectedAt());

        $user->setConnectedAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $user->getConnectedAt()->format('Y-m-d H:i:s')
        );
    }

    /**
     * Test que la date de la dernière connexion puisse être nulle.
     */
    public function testCanGetAndSetANullConnectedAt(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer(),
            connectedAt: new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $user->getConnectedAt()->format('Y-m-d H:i:s')
        );

        $user->setConnectedAt(null);

        self::assertNull($user->getConnectedAt());
    }


    /**
     * Test que l'on puisse ajouter un groupe.
     * @return \Aoo\Entity\User l'utilisateur.
     */
    public function testCanAddAGroup(): User
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer(),
            connectedAt: new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertEmpty($user->getGroups());

        $group = new Group('group-name', 'group-slug', null);
        $user->addGroup($group);

        self::assertCount(1, $user->getGroups());

        return $user;
    }

    /**
     * Test que l'on puisse retirer un groupe.
     * @param \App\Entity\User $user l'utilisateur.
     */
    #[PA\Depends('testCanAddAGroup')]
    public function testCanRemoveAGroup(User $user): void
    {
        $groups = $user->getGroups();

        $user->removeGroup($groups[0]);

        self::assertEmpty($user->getGroups());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même groupe deux fois.
     */
    public function testCanNotAddTheSameGroupTwice(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer(),
            connectedAt: new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertEmpty($user->getGroups());

        $group = new Group('group-name', 'group-slug', null);
        $user->addGroup($group);

        self::assertCount(1, $user->getGroups());

        $user->addGroup($group);

        self::assertCount(1, $user->getGroups());
    }


    /**
     * Test que les rôles soient accessible.
     */
    public function testCanGetAndSetRoles(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertEmpty($user->getRoles());

        $roles = ['ROLE_TEST'];
        $user->setRoles($roles);

        self::assertSame($roles, $user->getRoles());
    }


    /**
     * Test que l'identifiant utilisateur soit accessible.
     */
    public function testCanGetUserIdentifier(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertSame('user-login', $user->getUserIdentifier());
    }


    /**
     * Test que l'on puisse effacer le mot de passe en clair.
     */
    public function testCanEraseCredentials(): void
    {
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForEmployer()
        );

        self::assertNull($user->eraseCredentials());
    }
}

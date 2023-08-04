<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Employer;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Employer.
 */
#[
    PA\CoversClass(Employer::class),
    PA\UsesClass(User::class),
    PA\Group('entities'),
    PA\Group('entities_employer'),
    PA\Group('employer')
]
final class EmployerTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertNull($employer->getId());
    }


    /**
     * Test que le numéro SIREN soit accessible.
     */
    public function testCanGetAndSetSiren(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertSame('employer-siren', $employer->getSiren());

        $employer->setSiren('new-siren');

        self::assertSame('new-siren', $employer->getSiren());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertSame('employer-name', $employer->getName());

        $employer->setName('new-name');

        self::assertSame('new-name', $employer->getName());
    }


    /**
     * Test que la longitude soit accessible.
     */
    public function testCanGetAndSetLongitude(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertSame(1.23456789, $employer->getLongitude());

        $employer->setLongitude(9.87654321);

        self::assertSame(9.87654321, $employer->getLongitude());
    }


    /**
     * Test que la latitude soit accessible.
     */
    public function testCanGetAndSetLatitude(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertSame(1.2345678, $employer->getLatitude());

        $employer->setLatitude(8.7654321);

        self::assertSame(8.7654321, $employer->getLatitude());
    }


    /**
     * Test que l'on puisse ajouter un utilisateur.
     * @return \Aoo\Entity\Employer l'employeur.
     */
    public function testCanAddAUser(): Employer
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertEmpty($employer->getUsers());

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
        $employer->addUser($user);

        self::assertCount(1, $employer->getUsers());

        return $employer;
    }

    /**
     * Test que l'on puisse retirer un utilisateur.
     * @param \App\Entity\Employer $employer l'employeur.
     */
    #[PA\Depends('testCanAddAUser')]
    public function testCanRemoveAUser(Employer $employer): void
    {
        $users = $employer->getUsers();

        $employer->removeUser($users[0]);

        self::assertEmpty($employer->getUsers());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même utilisateur deux fois.
     */
    public function testCanNotAddTheSameUserTwice(): void
    {
        $employer = new Employer(
            'employer-siren',
            'employer-name',
            1.23456789,
            1.2345678
        );

        self::assertEmpty($employer->getUsers());

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
        $employer->addUser($user);

        self::assertCount(1, $employer->getUsers());

        $employer->addUser($user);

        self::assertCount(1, $employer->getUsers());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Role;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Role.
 */
#[
    PA\CoversClass(Role::class),
    PA\Group('entities'),
    PA\Group('entities_role'),
    PA\Group('role')
]
final class RoleTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $role = new Role('role-name', null);

        self::assertNull($role->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $role = new Role('role-name', null);

        self::assertSame('role-name', $role->getName());

        $role->setName('new-name');

        self::assertSame('new-name', $role->getName());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $role = new Role('role-name', null);

        self::assertNull($role->getDescription());

        $role->setDescription('new-description');

        self::assertSame('new-description', $role->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $role = new Role('role-name', 'role-description');

        self::assertSame('role-description', $role->getDescription());

        $role->setDescription(null);

        self::assertNull($role->getDescription());
    }
}

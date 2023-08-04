<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Status;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Status.
 */
#[
    PA\CoversClass(Status::class),
    PA\Group('entities'),
    PA\Group('entities_status'),
    PA\Group('status')
]
final class StatusTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $status = new Status('status-name', 'status-slug', null);

        self::assertNull($status->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $status = new Status('status-name', 'status-slug', null);

        self::assertSame('status-name', $status->getName());

        $status->setName('new-name');

        self::assertSame('new-name', $status->getName());
    }


    /**
     * Test que le slug soit accessible.
     */
    public function testCanGetAndSetSlug(): void
    {
        $status = new Status('status-name', 'status-slug', null);

        self::assertSame('status-slug', $status->getSlug());

        $status->setSlug('new-slug');

        self::assertSame('new-slug', $status->getSlug());
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $status = new Status('status-name', 'status-slug', null);

        self::assertNull($status->getDescription());

        $status->setDescription('new-description');

        self::assertSame('new-description', $status->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $status = new Status('status-name', 'status-slug', 'status-description');

        self::assertSame('status-description', $status->getDescription());

        $status->setDescription(null);

        self::assertNull($status->getDescription());
    }
}

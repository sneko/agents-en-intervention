<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Priority;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Priority.
 */
#[
    PA\CoversClass(Priority::class),
    PA\Group('entities'),
    PA\Group('entities_priority'),
    PA\Group('priority')
]
final class PriorityTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $priority = new Priority('priority-name', 'priority-slug');

        self::assertNull($priority->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $priority = new Priority('priority-name', 'priority-slug');

        self::assertSame('priority-name', $priority->getName());

        $priority->setName('new-name');

        self::assertSame('new-name', $priority->getName());
    }


    /**
     * Test que le slug soit accessible.
     */
    public function testCanGetAndSetSlug(): void
    {
        $priority = new Priority('priority-name', 'priority-slug');

        self::assertSame('priority-slug', $priority->getSlug());

        $priority->setSlug('new-slug');

        self::assertSame('new-slug', $priority->getSlug());
    }
}

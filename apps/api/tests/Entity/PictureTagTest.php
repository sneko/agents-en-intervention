<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\PictureTag;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité PictureTag.
 */
#[
    PA\CoversClass(PictureTag::class),
    PA\Group('entities'),
    PA\Group('entities_pictureTag'),
    PA\Group('pictureTag')
]
final class PictureTagTest extends TestCase
{
    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $pictureTag = new PictureTag('pictureTag-name', 'pictureTag-slug');

        self::assertNull($pictureTag->getId());
    }


    /**
     * Test que le nom soit accessible.
     */
    public function testCanGetAndSetName(): void
    {
        $pictureTag = new PictureTag('pictureTag-name', 'pictureTag-slug');

        self::assertSame('pictureTag-name', $pictureTag->getName());

        $pictureTag->setName('new-name');

        self::assertSame('new-name', $pictureTag->getName());
    }


    /**
     * Test que le slug soit accessible.
     */
    public function testCanGetAndSetSlug(): void
    {
        $pictureTag = new PictureTag('pictureTag-name', 'pictureTag-slug');

        self::assertSame('pictureTag-slug', $pictureTag->getSlug());

        $pictureTag->setSlug('new-slug');

        self::assertSame('new-slug', $pictureTag->getSlug());
    }
}

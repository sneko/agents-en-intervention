<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Picture;
use App\Entity\PictureTag;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Picture.
 */
#[
    PA\CoversClass(Picture::class),
    PA\UsesClass(PictureTag::class),
    PA\Group('entities'),
    PA\Group('entities_picture'),
    PA\Group('picture')
]
final class PictureTest extends TestCase
{
    // Traits :
    use InterventionMock;


    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertNull($picture->getId());
    }

    /**
     * Test que l'URL
     * soit initialisé à null.
     */
    public function testCanInitialiseUrlToNull(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertNull($picture->getURL());
    }


    /**
     * Test que le nom du fichier soit accessible.
     */
    public function testCanGetAndSetFileName(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertSame('picture-file-name', $picture->getFileName());

        $picture->setFileName('new-file');

        self::assertSame('new-file', $picture->getFileName());
    }


    /**
     * Test que l'intervention soit accessible.
     */
    public function testCanGetAndSetIntervention(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertSame($intervention, $picture->getIntervention());

        $otherIntervention = $this->getMockForIntervention();
        $picture->setIntervention($otherIntervention);

        self::assertSame($otherIntervention, $picture->getIntervention());
    }


    /**
     * Test que la date de création soit accessible.
     */
    public function testCanGetAndSetCreatedAt(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $picture->getCreatedAt()->format('Y-m-d H:i:s')
        );

        $picture->setCreatedAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $picture->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }


    /**
     * Test que l'URL soit accessible.
     */
    public function testCanGetAndSetUrl(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertNull($picture->getURL());

        $picture->setURL('new-URL');

        self::assertSame('new-URL', $picture->getURL());
    }

    /**
     * Test que l'URL puisse être nulle.
     */
    public function testCanGetAndSetANullURL(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'picture-URL'
        );

        self::assertSame('picture-URL', $picture->getURL());

        $picture->setURL(null);

        self::assertNull($picture->getURL());
    }


    /**
     * Test que l'on puisse ajouter un tag.
     * @return \Aoo\Entity\Picture la photo.
     */
    public function testCanAddATag(): Picture
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertEmpty($picture->getTags());

        $tag = new PictureTag('pictureTag-name', 'pictureTag-slug');
        $picture->addTag($tag);

        self::assertCount(1, $picture->getTags());

        return $picture;
    }

    /**
     * Test que l'on puisse retirer un tag.
     * @param \App\Entity\Picture $picture la photo.
     */
    #[PA\Depends('testCanAddATag')]
    public function testCanRemoveATag(Picture $picture): void
    {
        $tags = $picture->getTags();

        $picture->removeTag($tags[0]);

        self::assertEmpty($picture->getTags());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même tag deux fois.
     */
    public function testCanNotAddTheSameTagTwice(): void
    {
        $intervention = $this->getMockForIntervention();
        $picture = new Picture(
            'picture-file-name',
            $intervention,
            new \DateTimeImmutable('2023-01-01 00:00:00')
        );

        self::assertEmpty($picture->getTags());

        $tag = new PictureTag('pictureTag-name', 'pictureTag-slug');
        $picture->addTag($tag);

        self::assertCount(1, $picture->getTags());

        $picture->addTag($tag);

        self::assertCount(1, $picture->getTags());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Employer;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\Priority;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Intervention.
 */
#[
    PA\CoversClass(Intervention::class),
    PA\Group('entities'),
    PA\Group('entities_intervention'),
    PA\Group('intervention')
]
final class InterventionTest extends TestCase
{
    // Méthodes :

    /**
     * Renvoie un substitut de l'entité Status.
     * @return \App\Entity\Status un substitut de l'entité Status.
     */
    private function getMockForStatus(): Status
    {
        return $this->getMockBuilder(Status::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité Priority.
     * @return \App\Entity\Priority un substitut de l'entité Priority.
     */
    private function getMockForPriority(): Priority
    {
        return $this->getMockBuilder(Priority::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité Category.
     * @return \App\Entity\Category un substitut de l'entité Category.
     */
    private function getMockForCategory(): Category
    {
        return $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité Type.
     * @return \App\Entity\Type un substitut de l'entité Type.
     */
    private function getMockForType(): Type
    {
        return $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité User.
     * @return \App\Entity\User un substitut de l'entité User.
     */
    private function getMockForAuthor(): User
    {
        return $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

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
     * Renvoie un substitut de l'entité Location.
     * @return \App\Entity\Location un substitut de l'entité Location.
     */
    private function getMockForLocation(): Location
    {
        return $this->getMockBuilder(Location::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité User.
     * @return \App\Entity\User un substitut de l'entité User.
     */
    private function getMockForParticipant(): User
    {
        return $this->getMockForAuthor();
    }

    /**
     * Renvoie un substitut de l'entité Comment.
     * @return \App\Entity\Comment un substitut de l'entité Comment.
     */
    private function getMockForComment(): Comment
    {
        return $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Renvoie un substitut de l'entité Picture.
     * @return \App\Entity\Location un substitut de l'entité Picture.
     */
    private function getMockForPicture(): Picture
    {
        return $this->getMockBuilder(Picture::class)
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
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertNull($intervention->getId());
    }


    /**
     * Test que la date de création soit accessible.
     */
    public function testCanGetAndSetCreatedAt(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $intervention->getCreatedAt()->format('Y-m-d H:i:s')
        );

        $intervention->setCreatedAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $intervention->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }


    /**
     * Test que la description soit accessible.
     */
    public function testCanGetAndSetDescription(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            null,
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertNull($intervention->getDescription());

        $intervention->setDescription('new-description');

        self::assertSame('new-description', $intervention->getDescription());
    }

    /**
     * Test que la description puisse être nulle.
     */
    public function testCanGetAndSetANullDescription(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame('intervention-description', $intervention->getDescription());

        $intervention->setDescription(null);

        self::assertNull($intervention->getDescription());
    }


    /**
     * Test que le statut soit accessible.
     */
    public function testCanGetAndSetStatus(): void
    {
        $status = $this->getMockForStatus();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $status,
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame($status, $intervention->getStatus());

        $otherStatus = $this->getMockForStatus();
        $intervention->setStatus($otherStatus);

        self::assertSame($otherStatus, $intervention->getStatus());
    }


    /**
     * Test que la priorité soit accessible.
     */
    public function testCanGetAndSetPriority(): void
    {
        $priority = $this->getMockForPriority();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $priority,
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame($priority, $intervention->getPriority());

        $otherPriority = $this->getMockForPriority();
        $intervention->setPriority($otherPriority);

        self::assertSame($otherPriority, $intervention->getPriority());
    }


    /**
     * Test que la catégorie soit accessible.
     */
    public function testCanGetAndSetCategory(): void
    {
        $category = $this->getMockForCategory();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $category,
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame($category, $intervention->getCategory());

        $otherCategory = $this->getMockForCategory();
        $intervention->setCategory($otherCategory);

        self::assertSame($otherCategory, $intervention->getCategory());
    }


    /**
     * Test que le type soit accessible.
     */
    public function testCanGetAndSetType(): void
    {
        $type = $this->getMockForType();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $type,
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame($type, $intervention->getType());

        $otherType = $this->getMockForType();
        $intervention->setType($otherType);

        self::assertSame($otherType, $intervention->getType());
    }


    /**
     * Test que l'auteur soit accessible.
     */
    public function testCanGetAndSetAuthor(): void
    {
        $author = $this->getMockForAuthor();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $author,
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertSame($author, $intervention->getAuthor());

        $otherAuthor = $this->getMockForAuthor();
        $intervention->setAuthor($otherAuthor);

        self::assertSame($otherAuthor, $intervention->getAuthor());
    }


    /**
     * Test que l'auteur soit accessible.
     */
    public function testCanGetAndSetEmployer(): void
    {
        $employer = $this->getMockForEmployer();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $employer,
            $this->getMockForLocation()
        );

        self::assertSame($employer, $intervention->getEmployer());

        $otherEmployer = $this->getMockForEmployer();
        $intervention->setEmployer($otherEmployer);

        self::assertSame($otherEmployer, $intervention->getEmployer());
    }


    /**
     * Test que la localisation soit accessible.
     */
    public function testCanGetAndSetLocation(): void
    {
        $location = $this->getMockForLocation();
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $location
        );

        self::assertSame($location, $intervention->getLocation());

        $otherLocation = $this->getMockForLocation();
        $intervention->setLocation($otherLocation);

        self::assertSame($otherLocation, $intervention->getLocation());
    }


    /**
     * Test que l'on puisse ajouter un participant.
     * @return \Aoo\Entity\Intervention l'intervention.
     */
    public function testCanAddAParticipant(): Intervention
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getParticipants());

        $participant = $this->getMockForParticipant();
        $intervention->addParticipant($participant);

        self::assertCount(1, $intervention->getParticipants());

        return $intervention;
    }

    /**
     * Test que l'on puisse retirer un participant.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    #[PA\Depends('testCanAddAParticipant')]
    public function testCanRemoveAParticipant(Intervention $intervention): void
    {
        $participants = $intervention->getParticipants();

        $intervention->removeParticipant($participants[0]);

        self::assertEmpty($intervention->getParticipants());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même participant deux fois.
     */
    public function testCanNotAddTheSameParticipantTwice(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getParticipants());

        $participant = $this->getMockForParticipant();
        $intervention->addParticipant($participant);

        self::assertCount(1, $intervention->getParticipants());

        $intervention->addParticipant($participant);

        self::assertCount(1, $intervention->getParticipants());
    }


    /**
     * Test que l'on puisse ajouter un commentaire.
     * @return \Aoo\Entity\Intervention l'intervention.
     */
    public function testCanAddAComment(): Intervention
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getComments());

        $comment = $this->getMockForComment();
        $intervention->addComment($comment);

        self::assertCount(1, $intervention->getComments());

        return $intervention;
    }

    /**
     * Test que l'on puisse retirer un commentaire.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    #[PA\Depends('testCanAddAComment')]
    public function testCanRemoveAComment(Intervention $intervention): void
    {
        $comments = $intervention->getComments();

        $intervention->removeComment($comments[0]);

        self::assertEmpty($intervention->getComments());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même commentaire deux fois.
     */
    public function testCanNotAddTheSameCommentTwice(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getComments());

        $comment = $this->getMockForComment();
        $intervention->addComment($comment);

        self::assertCount(1, $intervention->getComments());

        $intervention->addComment($comment);

        self::assertCount(1, $intervention->getComments());
    }


    /**
     * Test que l'on puisse ajouter une photo.
     * @return \Aoo\Entity\Intervention l'intervention.
     */
    public function testCanAddAPicture(): Intervention
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getPictures());

        $picture = $this->getMockForPicture();
        $intervention->addPicture($picture);

        self::assertCount(1, $intervention->getPictures());

        return $intervention;
    }

    /**
     * Test que l'on puisse retirer un commentaire.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    #[PA\Depends('testCanAddAPicture')]
    public function testCanRemoveAPicture(Intervention $intervention): void
    {
        $pictures = $intervention->getPictures();

        $intervention->removePicture($pictures[0]);

        self::assertEmpty($intervention->getPictures());
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * le même commentaire deux fois.
     */
    public function testCanNotAddTheSamePictureTwice(): void
    {
        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            'intervention-description',
            $this->getMockForStatus(),
            $this->getMockForPriority(),
            $this->getMockForCategory(),
            $this->getMockForType(),
            $this->getMockForAuthor(),
            $this->getMockForEmployer(),
            $this->getMockForLocation()
        );

        self::assertEmpty($intervention->getPictures());

        $picture = $this->getMockForPicture();
        $intervention->addPicture($picture);

        self::assertCount(1, $intervention->getPictures());

        $intervention->addPicture($picture);

        self::assertCount(1, $intervention->getPictures());
    }
}

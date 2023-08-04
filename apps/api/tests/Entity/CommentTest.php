<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\User;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Comment.
 */
#[
    PA\CoversClass(Comment::class),
    PA\Group('entities'),
    PA\Group('entities_comment'),
    PA\Group('comment')
]
final class CommentTest extends TestCase
{
    // Traits :
    use InterventionMock;


    // Méthodes :

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
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            $this->getMockForAuthor(),
            $this->getMockForIntervention()
        );

        self::assertNull($comment->getId());
    }


    /**
     * Test que le message soit accessible.
     */
    public function testCanGetAndSetMessage(): void
    {
        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            $this->getMockForAuthor(),
            $this->getMockForIntervention()
        );

        self::assertSame('comment-message', $comment->getMessage());

        $comment->setMessage('new-message');

        self::assertSame('new-message', $comment->getMessage());
    }


    /**
     * Test que la date de création soit accessible.
     */
    public function testCanGetAndSetCreatedAt(): void
    {
        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            $this->getMockForAuthor(),
            $this->getMockForIntervention()
        );

        self::assertSame(
            '2023-01-01 00:00:00',
            $comment->getCreatedAt()->format('Y-m-d H:i:s')
        );

        $comment->setCreatedAt(new \DateTimeImmutable('2023-02-01 00:00:00'));

        self::assertSame(
            '2023-02-01 00:00:00',
            $comment->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }


    /**
     * Test que l'auteur soit accessible.
     */
    public function testCanGetAndSetAuthor(): void
    {
        $author = $this->getMockForAuthor();
        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            $author,
            $this->getMockForIntervention()
        );

        self::assertSame($author, $comment->getAuthor());

        $otherAuthor = $this->getMockForAuthor();
        $comment->setAuthor($otherAuthor);

        self::assertSame($otherAuthor, $comment->getAuthor());
    }


    /**
     * Test que l'intervention soit accessible.
     */
    public function testCanGetAndSetIntervention(): void
    {
        $intervention = $this->getMockForIntervention();
        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01 00:00:00'),
            $this->getMockForAuthor(),
            $intervention
        );

        self::assertSame($intervention, $comment->getIntervention());

        $otherIntervention = $this->getMockForIntervention();
        $comment->setIntervention($otherIntervention);

        self::assertSame($otherIntervention, $comment->getIntervention());
    }
}

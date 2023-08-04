<?php

declare(strict_types=1);

namespace App\Tests\Api\Interventions;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\PictureTag;
use App\Entity\Priority;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /interventions/{id}.
 */
#[
    PA\CoversClass(Intervention::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Comment::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Location::class),
    PA\UsesClass(Priority::class),
    PA\UsesClass(Picture::class),
    PA\UsesClass(PictureTag::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(Status::class),
    PA\UsesClass(Type::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_interventions'),
    PA\Group('api_interventions_get'),
    PA\Group('intervention')
]
final class InterventionGetTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/interventions/1');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/interventions/1" succeeded.');
    }


    /**
     * Ajoute une intervention.
     */
    private function addIntervention(): void
    {
        $status = new Status('status-name', 'status-slug', null);

        $priority = new Priority('priority-name');

        $category = new Category('category-name', null, null);

        $type = new Type('type-name', null, null, $category);

        $employer = new Employer('employer-siren', 'employer-name');

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

        $location = new Location(
            null,
            null,
            null,
            null,
            1.1,
            2.2
        );

        $intervention = new Intervention(
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            null,
            $status,
            $priority,
            $category,
            $type,
            $user,
            $employer,
            $location
        );
        $intervention->addParticipant($user);

        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            $user,
            $intervention
        );
        $intervention->addComment($comment);

        $picture = new Picture(
            'picture-file',
            $intervention,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00')
        );
        $intervention->addPicture($picture);

        $pictureTag = new PictureTag('pictureTag-name');
        $picture->addTag($pictureTag);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($status);
        $entityManager->persist($priority);
        $entityManager->persist($category);
        $entityManager->persist($type);
        $entityManager->persist($user);
        $entityManager->persist($employer);
        $entityManager->persist($location);
        $entityManager->persist($intervention);
        $entityManager->persist($comment);
        $entityManager->persist($pictureTag);
        $entityManager->persist($picture);
        $entityManager->flush();
    }

    /**
     * Test qu'une intervention puisse être renvoyée.
     */
    public function testCanGetAnIntervention(): void
    {
        $client = static::createClient();

        $this->addIntervention();

        $apiResponse = $client->request('GET', '/interventions/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/interventions/1" failed.');
        self::assertJson($apiResponse->getContent());

        $intervention = json_decode($apiResponse->getContent(), false);

        self::assertCount(14, (array) $intervention, 'Too much data has been returned.');

        self::assertSame(1, $intervention->id);
        self::assertSame('2023-01-01T00:00:00+01:00', $intervention->createdAt);

        $this->assertStatusIsComplete($intervention->status);
        $this->assertPriorityIsComplete($intervention->priority);
        $this->assertCategoryIsComplete($intervention->category);
        $this->assertTypeIsComplete($intervention->type);
        $this->assertAuthorIsComplete($intervention->author);
        $this->assertLocationIsComplete($intervention->location);


        self::assertIsArray($intervention->participants);
        self::assertArrayHasKey(0, $intervention->participants);

        foreach ($intervention->participants as $participant) {
            $this->assertParticipantIsComplete($participant);
        }


        self::assertIsArray($intervention->comments);
        self::assertArrayHasKey(0, $intervention->comments);

        foreach ($intervention->comments as $comment) {
            $this->assertCommentIsComplete($comment);
        }


        self::assertIsArray($intervention->pictures);
        self::assertArrayHasKey(0, $intervention->pictures);

        foreach ($intervention->pictures as $picture) {
            $this->assertPictureIsComplete($picture);
        }
    }

    /**
     * Les assertions du statut.
     * @param object $status le statut.
     */
    private function assertStatusIsComplete(object $status): void
    {
        self::assertCount(
            4,
            (array) $status,
            'Incorrect count of status data has been returned.'
        );

        self::assertSame(1, $status->id);
        self::assertSame('status-name', $status->name);

        $atId = '@id';
        self::assertSame('/status/status-slug', $status->$atId);
        $atType = '@type';
        self::assertSame('Status', $status->$atType);
    }

    /**
     * Les assertions de la priorité.
     * @param object $priority la priorité.
     */
    private function assertPriorityIsComplete(object $priority): void
    {
        self::assertCount(
            4,
            (array) $priority,
            'Incorrect count of priority data has been returned.'
        );

        self::assertSame(1, $priority->id);
        self::assertSame('priority-name', $priority->name);

        $atId = '@id';
        self::assertSame('/priorities/1', $priority->$atId);
        $atType = '@type';
        self::assertSame('Priority', $priority->$atType);
    }

    /**
     * Les assertions de la catégorie.
     * @param object $category la catégorie.
     */
    private function assertCategoryIsComplete(object $category): void
    {
        self::assertCount(
            4,
            (array) $category,
            'Incorrect count of catergory data has been returned.'
        );

        self::assertSame(1, $category->id);
        self::assertSame('category-name', $category->name);

        $atId = '@id';
        self::assertSame('/categories/1', $category->$atId);
        $atType = '@type';
        self::assertSame('Category', $category->$atType);
    }

    /**
     * Les assertions du type.
     * @param object $type du type.
     */
    private function assertTypeIsComplete(object $type): void
    {
        self::assertCount(
            4,
            (array) $type,
            'Incorrect count of type data has been returned.'
        );

        self::assertSame(1, $type->id);
        self::assertSame('type-name', $type->name);

        $atId = '@id';
        self::assertSame('/types/1', $type->$atId);
        $atType = '@type';
        self::assertSame('Type', $type->$atType);
    }

    /**
     * Les assertions de l'auteur.
     * @param object $author l'auteur.
     */
    private function assertAuthorIsComplete(object $author): void
    {
        self::assertCount(
            6,
            (array) $author,
            'Incorrect count of user data has been returned.'
        );

        self::assertSame(1, $author->id);
        self::assertSame('user-firstname', $author->firstname);
        self::assertSame('user-lastname', $author->lastname);
        self::assertSame('user-picture', $author->picture);

        $atId = '@id';
        self::assertSame('/users/1', $author->$atId);
        $atType = '@type';
        self::assertSame('User', $author->$atType);
    }

    /**
     * Les assertions de la localisation.
     * @param object $location la localisation.
     */
    private function assertLocationIsComplete(object $location): void
    {
        self::assertCount(
            5,
            (array) $location,
            'Incorrect count of location data has been returned.'
        );

        self::assertSame(1, $location->id);
        self::assertSame(1.1, $location->longitude);
        self::assertSame(2.2, $location->latitude);

        $atId = '@id';
        self::assertSame('/locations/1', $location->$atId);
        $atType = '@type';
        self::assertSame('Location', $location->$atType);
    }

    /**
     * Les assertions de l'intervenant.
     * @param object $participant l'intervenant.
     */
    private function assertParticipantIsComplete(object $participant): void
    {
        self::assertCount(
            6,
            (array) $participant,
            'Incorrect count of user data has been returned.'
        );
        self::assertSame(1, $participant->id);
        self::assertSame('user-firstname', $participant->firstname);
        self::assertSame('user-lastname', $participant->lastname);
        self::assertSame('user-picture', $participant->picture);

        $atId = '@id';
        self::assertSame('/users/' . $participant->id, $participant->$atId);
        $atType = '@type';
        self::assertSame('User', $participant->$atType);
    }

    /**
     * Les assertions du commentaire.
     * @param object $comment le commentaire.
     */
    private function assertCommentIsComplete(object $comment): void
    {
        self::assertCount(
            5,
            (array) $comment,
            'Incorrect count of comment data has been returned.'
        );

        self::assertSame('comment-message', $comment->message);
        self::assertSame('2023-01-01T00:00:00+01:00', $comment->createdAt);

        $this->assertAuthorIsComplete($comment->author);

        $atType = '@type';
        self::assertSame('Comment', $comment->$atType);
    }

    /**
     * Les assertions de la photo.
     * @param object $picture la photo.
     */
    private function assertPictureIsComplete(object $picture): void
    {
        self::assertCount(
            4,
            (array) $picture,
            'Incorrect count of picture data has been returned.'
        );

        self::assertSame('picture-file', $picture->file);


        self::assertIsArray($picture->tags);
        self::assertArrayHasKey(0, $picture->tags);

        foreach ($picture->tags as $tag) {
            $this->assertPictureTagIsComplete($tag);
        }

        $atType = '@type';
        self::assertSame('Picture', $picture->$atType);
    }

    /**
     * Les assertions de l'étiquette de la photo.
     * @param object $pictureTag l'étiquette de la photo.
     */
    private function assertPictureTagIsComplete(object $pictureTag): void
    {
        self::assertCount(
            3,
            (array) $pictureTag,
            'Incorrect count of pictureTag data has been returned.'
        );

        self::assertSame('pictureTag-name', $pictureTag->name);

        $atType = '@type';
        self::assertSame('PictureTag', $pictureTag->$atType);
    }
}

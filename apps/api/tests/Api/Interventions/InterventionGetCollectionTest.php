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
 * Test GET /interventions.
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
    PA\Group('api_interventions_get_collection'),
    PA\Group('intervention')
]
final class InterventionGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/interventions');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/interventions" succeeded.');
    }


    /**
     * Ajoute une intervention.
     */
    private function addInterventions(): void
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

        $intervention2 = new Intervention(
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
        $intervention2->addParticipant($user);
        $intervention2->addComment($comment);
        $intervention2->addPicture($picture);

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
        $entityManager->persist($intervention2);
        $entityManager->persist($comment);
        $entityManager->persist($pictureTag);
        $entityManager->persist($picture);
        $entityManager->flush();
    }

    /**
     * Test qu'une collection d'interventions puissent être renvoyée.
     */
    public function testCanGetAnInterventionCollection(): void
    {
        $client = static::createClient();

        $this->addInterventions();

        $apiResponse = $client->request('GET', '/interventions', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/interventions" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $interventions = $collection->$hydraMember;
        self::assertCount(2, $interventions);

        foreach ($interventions as $intervention) {
            $this->assertInterventionIsComplete($intervention);
        }
    }

    /**
     * Les assertions de l'intervention.
     * @param object $intervention l'intervention.
     */
    private function assertInterventionIsComplete(object $intervention): void
    {
        self::assertCount(
            10,
            (array) $intervention,
            'Incorrect count of intervention data has been returned.'
        );

        self::assertIsInt($intervention->id);
        self::assertIsString($intervention->createdAt);

        $this->assertCategoryIsComplete($intervention->category);
        $this->assertLocationIsComplete($intervention->location);
        $this->assertPriorityIsComplete($intervention->priority);
        $this->assertStatusIsComplete($intervention->status);

        self::assertIsArray($intervention->participants);

        foreach ($intervention->participants as $participant) {
            $this->assertParticipantIsComplete($participant);
        }

        self::assertIsArray($intervention->pictures);

        foreach ($intervention->pictures as $picture) {
            $this->assertPictureIsComplete($picture);
        }

        $atId = '@id';
        self::assertSame('/interventions/' . $intervention->id, $intervention->$atId);
        $atType = '@type';
        self::assertSame('Intervention', $intervention->$atType);
    }

    /**
     * Les assertions de la catégorie.
     * @param object $category la catégorie.
     */
    private function assertCategoryIsComplete(object $category): void
    {
        self::assertCount(
            3,
            (array) $category,
            'Incorrect count of catergory data has been returned.'
        );

        self::assertIsString($category->name);

        $atId = '@id';
        self::assertIsString($category->$atId);
        $atType = '@type';
        self::assertSame('Category', $category->$atType);
    }

    /**
     * Les assertions de la localisation.
     * @param object $location la localisation.
     */
    private function assertLocationIsComplete(object $location): void
    {
        self::assertCount(
            4,
            (array) $location,
            'Incorrect count of location data has been returned.'
        );

        self::assertIsFloat($location->longitude);
        self::assertIsFloat($location->latitude);

        $atId = '@id';
        self::assertIsString($location->$atId);
        $atType = '@type';
        self::assertSame('Location', $location->$atType);
    }

    /**
     * Les assertions de la priorité.
     * @param object $priority la priorité.
     */
    private function assertPriorityIsComplete(object $priority): void
    {
        self::assertCount(
            3,
            (array) $priority,
            'Incorrect count of priority data has been returned.'
        );

        self::assertIsString($priority->name);

        $atId = '@id';
        self::assertIsString($priority->$atId);
        $atType = '@type';
        self::assertSame('Priority', $priority->$atType);
    }

    /**
     * Les assertions du statut.
     * @param object $status le statut.
     */
    private function assertStatusIsComplete(object $status): void
    {
        self::assertCount(
            3,
            (array) $status,
            'Incorrect count of status data has been returned.'
        );

        self::assertIsString($status->name);

        $atId = '@id';
        self::assertIsString($status->$atId);
        $atType = '@type';
        self::assertSame('Status', $status->$atType);
    }

    /**
     * Les assertions de l'intervenant.
     * @param object $participant l'intervenant.
     */
    private function assertParticipantIsComplete(object $participant): void
    {
        self::assertCount(
            5,
            (array) $participant,
            'Incorrect count of user data has been returned.'
        );

        self::assertIsString($participant->firstname);
        self::assertIsString($participant->lastname);
        self::assertIsString($participant->picture);

        $atId = '@id';
        self::assertIsString($participant->$atId);
        $atType = '@type';
        self::assertSame('User', $participant->$atType);
    }

    /**
     * Les assertions de la photo.
     * @param object $picture la photo.
     */
    private function assertPictureIsComplete(object $picture): void
    {
        self::assertCount(
            3,
            (array) $picture,
            'Incorrect count of picture data has been returned.'
        );

        self::assertIsString($picture->file);

        $atId = '@id';
        self::assertIsString($picture->$atId);
        $atType = '@type';
        self::assertSame('Picture', $picture->$atType);
    }
}

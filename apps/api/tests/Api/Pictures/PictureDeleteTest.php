<?php

declare(strict_types=1);

namespace App\Tests\Api\Pictures;

use App\Entity\Category;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\Priority;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test DELETE /pictures/{id}.
 */
#[
    PA\CoversClass(Picture::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Intervention::class),
    PA\UsesClass(Location::class),
    PA\UsesClass(Priority::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(Status::class),
    PA\UsesClass(Type::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_pictures'),
    PA\Group('api_pictures_delete'),
    PA\Group('picture')
]
final class PictureDeleteTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('DELETE', '/pictures/1');

        self::assertSame(401, $apiResponse->getStatusCode(), 'DELETE "/pictures/1" succeeded.');
    }


    /**
     * Ajoute une photo.
     */
    private function addPicture(): void
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
            'intervention-description',
            $status,
            $priority,
            $category,
            $type,
            $user,
            $employer,
            $location
        );

        $picture = new Picture(
            'picture-file',
            $intervention,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00')
        );
        $intervention->addPicture($picture);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($status);
        $entityManager->persist($priority);
        $entityManager->persist($category);
        $entityManager->persist($type);
        $entityManager->persist($user);
        $entityManager->persist($employer);
        $entityManager->persist($location);
        $entityManager->persist($intervention);
        $entityManager->persist($picture);
        $entityManager->flush();
    }

    /**
     * Test qu'une photo puisse être supprimée.
     */
    public function testCanDeleteAPicture(): void
    {
        $client = static::createClient();

        $this->addPicture();

        $client->request('DELETE', '/pictures/1', ['auth_bearer' => $this->getJWT()]);
        $apiResponse = $client->getResponse();

        self::assertSame(204, $apiResponse->getStatusCode(), 'DELETE "/pictures/1" failed.');
        self::assertEmpty($apiResponse->getContent());
    }
}

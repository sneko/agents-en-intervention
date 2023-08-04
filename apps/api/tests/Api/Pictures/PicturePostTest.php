<?php

declare(strict_types=1);

namespace App\Tests\Api\Pictures;

use App\Entity\Category;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Picture;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Priority;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test POST /pictures.
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
    PA\Group('api_pictures_post'),
    PA\Group('picture')
]
final class PicturePostTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('POST', '/pictures');

        self::assertSame(401, $apiResponse->getStatusCode(), 'POST "/pictures" succeeded.');
    }


    /**
     * Ajoute les fixtures.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager le gestionnaire d'entité.
     */
    private function addFixtures(EntityManagerInterface $entityManager): void
    {
        $loader = new NativeLoader();
        $categorySet = $loader->loadFile(__DIR__ . '/../../../fixtures/category.yaml');

        foreach ($categorySet->getObjects() as $category) {
            $entityManager->persist($category);
        }


        $locationSet = $loader->loadFile(__DIR__ . '/../../../fixtures/location.yaml');

        foreach ($locationSet->getObjects() as $location) {
            $entityManager->persist($location);
        }


        $prioritySet = $loader->loadFile(__DIR__ . '/../../../fixtures/priority.yaml');

        foreach ($prioritySet->getObjects() as $priority) {
            $entityManager->persist($priority);
        }


        $typeSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/type.yaml',
            $categorySet->getParameters(),
            $categorySet->getObjects()
        );

        foreach ($typeSet->getObjects() as $type) {
            $entityManager->persist($type);
        }


        $statusSet = $loader->loadFile(__DIR__ . '/../../../fixtures/status.yaml');

        foreach ($statusSet->getObjects() as $status) {
            $entityManager->persist($status);
        }


        $employerSet = $loader->loadFile(__DIR__ . '/../../../fixtures/employer.yaml');

        foreach ($employerSet->getObjects() as $employer) {
            $entityManager->persist($employer);
        }


        $roleSet = $loader->loadFile(__DIR__ . '/../../../fixtures/role.yaml');

        foreach ($roleSet->getObjects() as $role) {
            $entityManager->persist($role);
        }


        $groupSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/group.yaml',
            $roleSet->getParameters(),
            $roleSet->getObjects()
        );

        foreach ($groupSet->getObjects() as $group) {
            $entityManager->persist($group);
        }


        $userSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/user.yaml',
            array_merge(
                $employerSet->getParameters(),
                $groupSet->getParameters()
            ),
            array_merge(
                $employerSet->getObjects(),
                $groupSet->getObjects()
            )
        );

        foreach ($userSet->getObjects() as $user) {
            $entityManager->persist($user);
        }


        $interventionSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/intervention.yaml',
            array_merge(
                $statusSet->getParameters(),
                $prioritySet->getParameters(),
                $categorySet->getParameters(),
                $typeSet->getParameters(),
                $userSet->getParameters(),
                $employerSet->getParameters(),
                $locationSet->getParameters()
            ),
            array_merge(
                $statusSet->getObjects(),
                $prioritySet->getObjects(),
                $categorySet->getObjects(),
                $typeSet->getObjects(),
                $userSet->getObjects(),
                $employerSet->getObjects(),
                $locationSet->getObjects()
            )
        );

        foreach ($interventionSet->getObjects() as $intervention) {
            $entityManager->persist($intervention);
        }
        $entityManager->flush();
    }

    /**
     * Renvoie les données de la photo.
     * @return array Les données de la photo.
     */
    private function pictureToPost(): array
    {
        $createdAt = new \DateTimeImmutable('2023-01-01T00:00:00+00:00');

        return [
            'file' => 'picture-file',
            'intervention' => '/interventions/1',
            'createdAt' => $createdAt->format(\DateTimeInterface::ISO8601)
        ];
    }

    /**
     * Test que des photos puissent être enregistrées.
     */
    public function testCanPostPictures(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request(
            'POST',
            '/pictures',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $this->pictureToPost()
            ]
        );

        self::assertSame(201, $apiResponse->getStatusCode(), 'POST to "/pictures" failed.');
        self::assertJson($apiResponse->getContent());

        $jsonResponse = json_decode($apiResponse->getContent(), false);

        self::assertSame(1, $jsonResponse->id);
        $atId = '@id';
        self::assertSame('/pictures/1', $jsonResponse->$atId);
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une photo avec une intervention inconnue.
     */
    public function testCanNotPostAPictureWithANonExistantIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $pictureDatas = $this->pictureToPost();
        $pictureDatas['intervention'] = '/interventions/999';

        $apiResponse = $client->request(
            'POST',
            '/pictures',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $pictureDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/pictures" did not failed.');
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Api\Interventions;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Employer;
use App\Entity\Group;
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
 * Test PATCH /interventions/1.
 */
#[
    PA\CoversClass(Intervention::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Comment::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Location::class),
    PA\UsesClass(Priority::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(Status::class),
    PA\UsesClass(Type::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_interventions'),
    PA\Group('api_interventions_patch'),
    PA\Group('intervention')
]
final class InterventionPatchTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('PATCH', '/interventions/1');

        self::assertSame(401, $apiResponse->getStatusCode(), 'PATCH "/interventions/1" succeeded.');
    }


    /**
     * Ajoute les fixtures.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager le gestionnaire d'entité.
     */
    private function addFixtures(EntityManagerInterface $entityManager): void
    {
        $loader = new NativeLoader();
        $locationSet = $loader->loadFile(__DIR__ . '/../../../fixtures/location.yaml');

        foreach ($locationSet->getObjects() as $location) {
            $entityManager->persist($location);
        }

        $categorySet = $loader->loadFile(__DIR__ . '/../../../fixtures/category.yaml');

        foreach ($categorySet->getObjects() as $category) {
            $entityManager->persist($category);
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
        $entityManager->flush();
    }

    /**
     * Ajoute une intervention.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager le gestionnaire d'entité.
     */
    private function addIntervention(EntityManagerInterface $entityManager): void
    {
        $status = $entityManager->find(Status::class, 1);

        $priority = $entityManager->find(Priority::class, 1);

        $category = $entityManager->find(Category::class, 1);

        $type = $entityManager->find(Type::class, 1);

        $user = $entityManager->find(User::class, 1);

        $employer = $entityManager->find(Employer::class, 1);

        $location = $entityManager->find(Location::class, 1);

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
        $intervention->addParticipant($user);

        $comment = new Comment(
            'comment-message',
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            $user,
            $intervention
        );
        $intervention->addComment($comment);

        $entityManager->persist($intervention);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    /**
     * Renvoie les données de l'intervention.
     * @return array Les données de l'intervention.
     */
    private function interventionToPatch(): array
    {
        return [
            'category' => '/categories/2',
            'description' => 'intervention-description-2',
            'location' => [
                'street' => 'location-street-2',
                'rest' => 'location-rest-2',
                'postcode' => '75002',
                'city' => 'location-city-2',
                'longitude' => '2.345678',
                'latitude' => '2.34567'
            ],
            'participants' => [
                '/users/4',
                '/users/5'
            ],
            'priority' => '/priorities/2',
            'type' => '/types/2',
            'status' => '/status/finished'
        ];
    }

    /**
     * Test qu'une intervention puisse être complètement mise à jour.
     */
    public function testCanPatchAFullIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $this->interventionToPatch()
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * l'auteur d'une intervention.
     */
    public function testCanPatchOnlyTheAuthorOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['author' => '/users/2'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * la catégorie d'une intervention.
     */
    public function testCanPatchOnlyTheCategoryOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['category' => '/categories/2'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * la description d'une intervention.
     */
    public function testCanPatchOnlyTheDescriptionOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['description' => 'intervention-description-2'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }

    /**
     * Test que l'on puisse mettre à jour seulement
     * la description d'une intervention.
     */
    public function testCanPatchOnlyTheDescriptionOfAnInterventionWithBlank(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['description' => null];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * la localisation d'une intervention.
     */
    public function testCanPatchOnlyTheLocationOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = [
            'location' => [
                'street' => 'location-street-2',
                'rest' => 'location-rest-2',
                'postcode' => '75002',
                'city' => 'location-city-2',
                'longitude' => '2.345678',
                'latitude' => '2.34567'
            ]
        ];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * les participants d'une intervention.
     */
    public function testCanPatchOnlyTheParticipantsOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = [
            'participants' => [
                '/users/4',
                '/users/5'
            ]
        ];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * la priorité d'une intervention.
     */
    public function testCanPatchOnlyThePriorityOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['priority' => '/priorities/2'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * le type d'une intervention.
     */
    public function testCanPatchOnlyTheTypeOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['type' => '/types/2'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on puisse mettre à jour seulement
     * le statut d'une intervention.
     */
    public function testCanPatchOnlyTheStatusOfAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = ['status' => '/status/finished'];

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(204, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" failed.');
        self::assertEmpty($apiResponse->getContent(), 'The response must not have a body.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un statut inconnu.
     */
    public function testCanNotPatchAnInterventionWithANonExistantStatus(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['status'] = '/status/non-existant-status';

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une catégorie inconnue.
     */
    public function testCanNotPatchAnInterventionWithANonExistantCategory(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['category'] = '/categories/999';

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une priorité inconnue.
     */
    public function testCanNotPatchAnInterventionWithANonExistantPriority(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['priority'] = '/priorities/999';

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un type inconnu.
     */
    public function testCanNotPatchAnInterventionWithANonExistantType(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['type'] = '/types/999';

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une rue
     * de plus de 100 caractères.
     */
    public function testCanNotPatchAnInterventionWithAnOver100CharactersStreet(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['street'] = '';

        for ($caracterCount = 1; $caracterCount < 102; $caracterCount++) {
            $interventionDatas['location']['street'] .= 'a';
        }

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.street', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'Le nom de la rue ne doit pas être plus long que 100 caractères.',
            $jsonResponse->violations[0]->message
        );
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un complément d'adresse
     * de plus de 500 caractères.
     */
    public function testCanNotPatchAnInterventionWithAnOver500CharactersRest(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['rest'] = '';

        for ($caracterCount = 1; $caracterCount < 502; $caracterCount++) {
            $interventionDatas['location']['rest'] .= 'a';
        }

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.rest', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'Le complément d\'adresse ne doit pas être plus long que 500 caractères.',
            $jsonResponse->violations[0]->message
        );
    }


    /**
     * Renvoie des codes patchaux invalides.
     * @return string[] des codes patchaux invalides.
     */
    public static function getInvalidPostcodes(): array
    {
        return [
            'letters only' => ['aaaaa'],
            'alphanumeric characters' => ['aa111'],
            'too many digits' => ['111111'],
            'not enough digits' => ['1111']
        ];
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un code postal invalide.
     * @param string $invalidPostcode un code postal invalide.
     */
    #[PA\DataProvider('getInvalidPostcodes')]
    public function testCanNotPatchAnInvalidPostcode(string $invalidPostcode): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['postcode'] = $invalidPostcode;

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.postcode', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'Le code postal doit être constitué de 5 chiffres.',
            $jsonResponse->violations[0]->message
        );
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une ville
     * de plus de 163 caractères.
     */
    public function testCanNotPatchAnInterventionWithAnOver163CharactersCity(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['city'] = '';

        for ($caracterCount = 1; $caracterCount < 165; $caracterCount++) {
            $interventionDatas['location']['city'] .= 'a';
        }

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.city', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'Le nom de la ville ne doit pas être plus long que 163 caractères.',
            $jsonResponse->violations[0]->message
        );
    }


    /**
     * Renvoie des longitudes hors limites.
     * @return string[] des longitudes hors limites.
     */
    public static function getNotInRangeLongitudes(): array
    {
        return [
            'too high' => ['180.1'],
            'too low' => ['-180.1']
        ];
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une longitude hors limites.
     * @param string $notInRangeLongitude une longitude hors limites.
     */
    #[PA\DataProvider('getNotInRangeLongitudes')]
    public function testCanNotPatchAnInvalidLongitude(string $notInRangeLongitude): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['longitude'] = $notInRangeLongitude;

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.longitude', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'La longitude doit être comprise entre -180 et 180.',
            $jsonResponse->violations[0]->message
        );
    }


    /**
     * Renvoie des latitudes hors limites.
     * @return string[] des latitudes hors limites.
     */
    public static function getNotInRangeLatitudes(): array
    {
        return [
            'too high' => ['90.1'],
            'too low' => ['-90.1']
        ];
    }

    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une latitude hors limites.
     * @param string $notInRangeLatitude une latitude hors limites.
     */
    #[PA\DataProvider('getNotInRangeLatitudes')]
    public function testCanNotPatchAnInvalidLatitude(string $notInRangeLatitude): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());
        $this->addIntervention($doctrine->getManager());

        $interventionDatas = $this->interventionToPatch();
        $interventionDatas['location']['latitude'] = $notInRangeLatitude;

        $apiResponse = $client->request(
            'PATCH',
            '/interventions/1',
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'PATCH to "/interventions/1" did not failed.');
        self::assertJson($apiResponse->getContent(false));

        $jsonResponse = json_decode($apiResponse->getContent(false), false);

        self::assertIsArray($jsonResponse->violations);
        self::assertCount(1, $jsonResponse->violations);
        self::assertArrayHasKey(0, $jsonResponse->violations);
        self::assertSame('location.latitude', $jsonResponse->violations[0]->propertyPath);
        self::assertSame(
            'La latitude doit être comprise entre -90 et 90.',
            $jsonResponse->violations[0]->message
        );
    }
}

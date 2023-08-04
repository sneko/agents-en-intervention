<?php

declare(strict_types=1);

namespace App\Tests\Api\Interventions;

use App\Entity\Category;
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
 * Test POST /interventions.
 */
#[
    PA\CoversClass(Intervention::class),
    PA\UsesClass(Category::class),
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
    PA\Group('api_interventions_post'),
    PA\Group('intervention')
]
final class InterventionPostTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('POST', '/interventions');

        self::assertSame(401, $apiResponse->getStatusCode(), 'POST "/interventions" succeeded.');
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
     * Renvoie les données de l'intervention.
     * @return array Les données de l'intervention.
     */
    private function interventionToPost(): array
    {
        $createdAt = new \DateTimeImmutable('2023-01-01T00:00:00+00:00');

        return [
            'author' => '/users/1',
            'createdAt' => $createdAt->format(\DateTimeInterface::ISO8601),
            'description' => 'intervention-description',
            'category' => '/categories/1',
            'employer' => '/employers/1',
            'location' => [
                'street' => 'location-street',
                'rest' => 'location-rest',
                'postcode' => '75000',
                'city' => 'location-city',
                'longitude' => "1.234567",
                'latitude' => "1.23456"
            ],
            'participants' => [
                '/users/2',
                '/users/3'
            ],
            'priority' => '/priorities/1',
            'type' => '/types/1',
            'status' => '/status/to-do'
        ];
    }

    /**
     * Test qu'une intervention puisse être enregistrée.
     */
    public function testCanPostAnIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $this->interventionToPost()
            ]
        );

        self::assertSame(201, $apiResponse->getStatusCode(), 'POST to "/interventions" failed.');
        self::assertJson($apiResponse->getContent());

        $jsonResponse = json_decode($apiResponse->getContent(), false);

        self::assertSame(1, $jsonResponse->id);
    }

    /**
     * Test qu'une intervention puisse être enregistrée
     * avec une description vide.
     */
    public function testCanPostAnInterventionWithABlankDescription(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        unset($interventionDatas['description']);

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $this->interventionToPost()
            ]
        );

        self::assertSame(201, $apiResponse->getStatusCode(), 'POST to "/interventions" failed.');
        self::assertJson($apiResponse->getContent());

        $jsonResponse = json_decode($apiResponse->getContent(), false);

        self::assertSame(1, $jsonResponse->id);
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention d'auteur inconnu.
     */
    public function testCanNotPostAnInterventionFromANonExistantAuthor(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['author'] = '/users/999';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un statut inconnu.
     */
    public function testCanNotPostAnInterventionWithANonExistantStatus(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['status'] = '/status/non-existant-status';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une catégorie inconnue.
     */
    public function testCanNotPostAnInterventionWithANonExistantCategory(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['category'] = '/categories/999';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un employeur inconnu.
     */
    public function testCanNotPostAnInterventionWithANonExistantEmployer(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['employer'] = '/employers/999';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une priorité inconnue.
     */
    public function testCanNotPostAnInterventionWithANonExistantPriority(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['priority'] = '/priorities/999';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec un type inconnu.
     */
    public function testCanNotPostAnInterventionWithANonExistantType(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['type'] = '/types/999';

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * une intervention avec une rue
     * de plus de 100 caractères.
     */
    public function testCanNotPostAnInterventionWithAnOver100CharactersStreet(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['street'] = '';

        for ($caracterCount = 1; $caracterCount < 102; $caracterCount++) {
            $interventionDatas['location']['street'] .= 'a';
        }

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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
    public function testCanNotPostAnInterventionWithAnOver500CharactersRest(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['rest'] = '';

        for ($caracterCount = 1; $caracterCount < 502; $caracterCount++) {
            $interventionDatas['location']['rest'] .= 'a';
        }

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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
     * Renvoie des codes postaux invalides.
     * @return string[] des codes postaux invalides.
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
    public function testCanNotPostAnInvalidPostcode(string $invalidPostcode): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['postcode'] = $invalidPostcode;

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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
    public function testCanNotPostAnInterventionWithAnOver163CharactersCity(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['city'] = '';

        for ($caracterCount = 1; $caracterCount < 165; $caracterCount++) {
            $interventionDatas['location']['city'] .= 'a';
        }

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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
    public function testCanNotPostAnInvalidLongitude(string $notInRangeLongitude): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['longitude'] = $notInRangeLongitude;

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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
    public function testCanNotPostAnInvalidLatitude(string $notInRangeLatitude): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $interventionDatas = $this->interventionToPost();
        $interventionDatas['location']['latitude'] = $notInRangeLatitude;

        $apiResponse = $client->request(
            'POST',
            '/interventions',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $interventionDatas
            ]
        );

        self::assertSame(422, $apiResponse->getStatusCode(), 'POST to "/interventions" did not failed.');
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

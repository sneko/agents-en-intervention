<?php

declare(strict_types=1);

namespace App\Tests\Api\Types;

use App\Entity\Category;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /types.
 */
#[
    PA\CoversClass(Type::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_types'),
    PA\Group('api_types_get_collection'),
    PA\Group('type')
]
final class TypeGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/types');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/types" succeeded.');
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
        $entityManager->flush();


        $typeSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/type.yaml',
            $categorySet->getParameters(),
            $categorySet->getObjects()
        );

        foreach ($typeSet->getObjects() as $type) {
            $entityManager->persist($type);
        }
        $entityManager->flush();
    }

    /**
     * Test qu'une collection de types puissent être renvoyée.
     */
    public function testCanGetATypeCollection(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request('GET', '/types', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/types" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $types = $collection->$hydraMember;

        foreach ($types as $type) {
            $this->assertTypeIsComplete($type);
        }
    }

    /**
     * Les assertions du type.
     * @param object $type le type.
     */
    private function assertTypeIsComplete(object $type): void
    {
        self::assertGreaterThanOrEqual(
            5,
            count((array) $type),
            'Incorrect count of type data has been returned.'
        );

        self::assertIsInt($type->id);
        self::assertIsString($type->name);
        self::assertIsString($type->category);

        $atId = '@id';
        self::assertSame('/types/' . $type->id, $type->$atId);
        $atType = '@type';
        self::assertSame('Type', $type->$atType);
    }
}

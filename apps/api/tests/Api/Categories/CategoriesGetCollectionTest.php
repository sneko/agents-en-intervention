<?php

declare(strict_types=1);

namespace App\Tests\Api\Categories;

use App\Entity\Category;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /categories.
 */
#[
    PA\CoversClass(Category::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_categories'),
    PA\Group('api_categories_get_collection'),
    PA\Group('category')
]
final class CategoriesGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/categories');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/categories" succeeded.');
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
    }

    /**
     * Test qu'une collection de catégories puissent être renvoyée.
     */
    public function testCanGetACategoryCollection(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request('GET', '/categories', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/categories" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $categories = $collection->$hydraMember;
        self::assertCount(6, $categories);

        foreach ($categories as $category) {
            $this->assertCategoryIsComplete($category);
        }
    }

    /**
     * Les assertions de la catégorie.
     * @param object $category la catégorie.
     */
    private function assertCategoryIsComplete(object $category): void
    {
        self::assertGreaterThanOrEqual(
            4,
            count((array) $category),
            'Incorrect count of category data has been returned.'
        );

        self::assertIsInt($category->id);
        self::assertIsString($category->name, 'The name in not a string.');

        $atId = '@id';
        self::assertSame('/categories/' . $category->id, $category->$atId);
        $atType = '@type';
        self::assertSame('Category', $category->$atType);
    }
}

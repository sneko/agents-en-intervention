<?php

declare(strict_types=1);

namespace App\Tests\Api\Priorities;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\Priority;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /priorities.
 */
#[
    PA\CoversClass(Priority::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_priorities'),
    PA\Group('api_priorities_get_collection'),
    PA\Group('priority')
]
final class PriorityGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/priorities');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/priorities" succeeded.');
    }


    /**
     * Ajoute les fixtures.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager le gestionnaire d'entité.
     */
    private function addFixtures(EntityManagerInterface $entityManager): void
    {
        $loader = new NativeLoader();

        $prioritySet = $loader->loadFile(__DIR__ . '/../../../fixtures/priority.yaml');

        foreach ($prioritySet->getObjects() as $priority) {
            $entityManager->persist($priority);
        }
        $entityManager->flush();
    }

    /**
     * Test qu'une collection de priorités puissent être renvoyée.
     */
    public function testCanGetAPriorityCollection(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request('GET', '/priorities', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/priorities" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $priorities = $collection->$hydraMember;
        self::assertCount(2, $priorities);

        foreach ($priorities as $priority) {
            $this->assertPriorityIsComplete($priority);
        }
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

        self::assertIsInt($priority->id);
        self::assertIsString($priority->name);

        $atId = '@id';
        self::assertSame('/priorities/' . $priority->id, $priority->$atId);
        $atType = '@type';
        self::assertSame('Priority', $priority->$atType);
    }
}

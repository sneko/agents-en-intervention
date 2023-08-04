<?php

declare(strict_types=1);

namespace App\Tests\Api\Status;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /status.
 */
#[
    PA\CoversClass(Status::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_status'),
    PA\Group('api_status_get_collection'),
    PA\Group('status')
]
final class StatusGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/status');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/status" succeeded.');
    }


    /**
     * Ajoute les fixtures.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager le gestionnaire d'entité.
     */
    private function addFixtures(EntityManagerInterface $entityManager): void
    {
        $loader = new NativeLoader();

        $statusSet = $loader->loadFile(__DIR__ . '/../../../fixtures/status.yaml');

        foreach ($statusSet->getObjects() as $status) {
            $entityManager->persist($status);
        }
        $entityManager->flush();
    }

    /**
     * Test qu'une collection de statuts puissent être renvoyée.
     */
    public function testCanGetAStatusCollection(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request('GET', '/status', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/status" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $statusCollection = $collection->$hydraMember;
        self::assertCount(5, $statusCollection);

        foreach ($statusCollection as $status) {
            $this->assertStatusIsComplete($status);
        }
    }

    /**
     * Les assertions du statut.
     * @param object $status le statut.
     */
    private function assertStatusIsComplete(object $status): void
    {
        self::assertGreaterThanOrEqual(
            5,
            count((array) $status),
            'Incorrect count of status data has been returned.'
        );

        self::assertIsInt($status->id);
        self::assertIsString($status->name);
        self::assertIsString($status->slug);

        $atId = '@id';
        self::assertSame('/status/' . $status->slug, $status->$atId);
        $atType = '@type';
        self::assertSame('Status', $status->$atType);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Api\Locations;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Location;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /locations.
 */
#[
    PA\CoversClass(Location::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_locations'),
    PA\Group('api_locations_get_collection'),
    PA\Group('location')
]
final class LocationGetCollectionTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/locations');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/locations" succeeded.');
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
        $entityManager->flush();
    }

    /**
     * Test qu'une collection de localisations puissent être renvoyée.
     */
    public function testCanGetALocationCollection(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request('GET', '/locations', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/locations" failed.');
        self::assertJson($apiResponse->getContent());

        $collection = json_decode($apiResponse->getContent(), false);

        $hydraMember = 'hydra:member';
        self::assertIsArray($collection->$hydraMember);
        $locations = $collection->$hydraMember;
        self::assertCount(30, $locations);

        foreach ($locations as $location) {
            $this->assertLocationIsComplete($location);
        }
    }

    /**
     * Les assertions de la localisation.
     * @param object $location la localisation.
     */
    private function assertLocationIsComplete(object $location): void
    {
        self::assertGreaterThanOrEqual(
            5,
            count((array) $location),
            'Incorrect count of location data has been returned.'
        );

        self::assertIsInt($location->id);
        self::assertIsFloat($location->longitude);
        self::assertIsFloat($location->latitude);

        $atId = '@id';
        self::assertSame('/locations/' . $location->id, $location->$atId);
        $atType = '@type';
        self::assertSame('Location', $location->$atType);
    }
}

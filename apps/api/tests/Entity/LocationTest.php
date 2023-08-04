<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Location;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Test l'entité Location.
 */
#[
    PA\CoversClass(Location::class),
    PA\Group('entities'),
    PA\Group('entities_location'),
    PA\Group('location')
]
final class LocationTest extends TestCase
{
    // Traits :
    use InterventionMock;


    // Méthodes :

    /**
     * Test que l'identifiant
     * soit initialisé à null.
     */
    public function testCanInitialiseIdentifierToNull(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertNull($location->getId());
    }


    /**
     * Test que la rue soit accessible.
     */
    public function testCanGetAndSetStreet(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678,
        );

        self::assertNull($location->getStreet());

        $location->setStreet('new-street');

        self::assertSame('new-street', $location->getStreet());
    }

    /**
     * Test que la rue puisse être nulle.
     */
    public function testCanGetAndSetANullStreet(): void
    {
        $location = new Location(
            'localtion-street',
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertSame('localtion-street', $location->getStreet());

        $location->setStreet(null);

        self::assertNull($location->getStreet());
    }


    /**
     * Test que le reste de l'adresse soit accessible.
     */
    public function testCanGetAndSetRest(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertNull($location->getRest());

        $location->setRest('new-rest');

        self::assertSame('new-rest', $location->getRest());
    }

    /**
     * Test que le reste de l'adresse puisse être nul.
     */
    public function testCanGetAndSetANullRest(): void
    {
        $location = new Location(
            null,
            'localtion-rest',
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertSame('localtion-rest', $location->getRest());

        $location->setRest(null);

        self::assertNull($location->getRest());
    }


    /**
     * Test que le code postal soit accessible.
     */
    public function testCanGetAndSetPostcode(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertNull($location->getPostcode());

        $location->setPostcode('new-postcode');

        self::assertSame('new-postcode', $location->getPostcode());
    }

    /**
     * Test que le code postal puisse être nul.
     */
    public function testCanGetAndSetANullPostcode(): void
    {
        $location = new Location(
            null,
            null,
            'localtion-postcode',
            null,
            1.23456789,
            1.2345678
        );

        self::assertSame('localtion-postcode', $location->getPostcode());

        $location->setPostcode(null);

        self::assertNull($location->getPostcode());
    }


    /**
     * Test que la ville soit accessible.
     */
    public function testCanGetAndSetCity(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertNull($location->getCity());

        $location->setCity('new-city');

        self::assertSame('new-city', $location->getCity());
    }

    /**
     * Test que la ville puisse être nulle.
     */
    public function testCanGetAndSetANullCity(): void
    {
        $location = new Location(
            null,
            null,
            null,
            'localtion-city',
            1.23456789,
            1.2345678
        );

        self::assertSame('localtion-city', $location->getCity());

        $location->setCity(null);

        self::assertNull($location->getCity());
    }


    /**
     * Test que la longitude soit accessible.
     */
    public function testCanGetAndSetLongitude(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertSame(1.23456789, $location->getLongitude());

        $location->setLongitude(9.87654321);

        self::assertSame(9.87654321, $location->getLongitude());
    }


    /**
     * Test que la latitude soit accessible.
     */
    public function testCanGetAndSetLatitude(): void
    {
        $location = new Location(
            null,
            null,
            null,
            null,
            1.23456789,
            1.2345678
        );

        self::assertSame(1.2345678, $location->getLatitude());

        $location->setLatitude(8.7654321);

        self::assertSame(8.7654321, $location->getLatitude());
    }
}

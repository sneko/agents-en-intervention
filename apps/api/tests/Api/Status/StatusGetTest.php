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
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /status/{id}.
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
    PA\Group('api_status_get'),
    PA\Group('status')
]
final class StatusGetTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/status/1');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/status/1" succeeded.');
    }


    /**
     * Test qu'un statut puisse être renvoyé.
     */
    public function testCanGetAStatus(): void
    {
        $client = static::createClient();
        $newStatus = new Status('status-name', 'status-slug', 'status-description');

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($newStatus);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/status/status-slug', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/status/status-slug" failed.');
        self::assertJson($apiResponse->getContent());

        $status = json_decode($apiResponse->getContent(), false);

        self::assertCount(7, (array) $status, 'Too much data has been returned.');

        self::assertSame(1, $status->id);
        self::assertSame('status-name', $status->name);
        self::assertSame('status-slug', $status->slug);
        self::assertSame('status-description', $status->description);

        $atId = '@id';
        self::assertSame('/status/status-slug', $status->$atId);
        $atType = '@type';
        self::assertSame('Status', $status->$atType);
    }

    /**
     * Test qu'un statut puisse être renvoyé.
     */
    public function testCanGetAStatusWithoutDescription(): void
    {
        $client = static::createClient();
        $newStatus = new Status('status-name', 'status-slug', null);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($newStatus);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/status/status-slug', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/status/status-slug" failed.');
        self::assertJson($apiResponse->getContent());

        $status = json_decode($apiResponse->getContent(), false);

        self::assertCount(6, (array) $status, 'Too much data has been returned.');

        self::assertSame(1, $status->id);
        self::assertSame('status-name', $status->name);
        self::assertSame('status-slug', $status->slug);

        $atId = '@id';
        self::assertSame('/status/status-slug', $status->$atId);
        $atType = '@type';
        self::assertSame('Status', $status->$atType);
    }
}

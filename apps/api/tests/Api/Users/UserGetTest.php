<?php

declare(strict_types=1);

namespace App\Tests\Api\Users;

use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test GET /users/{id}.
 */
#[
    PA\CoversClass(User::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_users'),
    PA\Group('api_users_get'),
    PA\Group('user')
]
final class UserGetTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('GET', '/users/1');

        self::assertSame(401, $apiResponse->getStatusCode(), 'GET "/users/1" succeeded.');
    }


    /**
     * Ajoute un employeur.
     * @return \App\Entity\Employer l'employeur.
     */
    private function getEmployer(): Employer
    {
        return new Employer('employer-siren', 'employer-name');
    }

    /**
     * Ajoute un utilisateur.
     * @param \App\Entity\Employer $employer l'employeur.
     * @return \App\Entity\User l'utilisateur.
     */
    private function getUser(Employer $employer): User
    {
        return new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            'user-email',
            'user-phoneNumber',
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'user-picture',
            $employer,
            connectedAt: new \DateTimeImmutable('2023-01-02T00:00:00+00:00')
        );
    }

    /**
     * Test qu'un utilisateur puisse être renvoyé.
     */
    public function testCanGetAUser(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($this->getUser($employer));
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(17, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-email', $user->email);
        self::assertSame('user-phoneNumber', $user->phoneNumber);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt, 'createAt');
        self::assertSame('user-picture', $user->picture);
        self::assertSame('/employers/1', $user->employer);
        self::assertTrue($user->active);
        self::assertSame('2023-01-02T00:00:00+01:00', $user->connectedAt, 'connectedAt');
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }

    /**
     * Test qu'un utilisateur sans courriel puisse être renvoyé.
     */
    public function testCanGetAUserWithoutEmail(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $newUser = $this->getUser($employer);
        $newUser->setEmail(null);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($newUser);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(16, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-phoneNumber', $user->phoneNumber);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt, 'createAt');
        self::assertSame('user-picture', $user->picture);
        self::assertSame('/employers/1', $user->employer);
        self::assertTrue($user->active);
        self::assertSame('2023-01-02T00:00:00+01:00', $user->connectedAt, 'connectedAt');
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }

    /**
     * Test qu'un utilisateur sans numéro de téléphone puisse être renvoyé.
     */
    public function testCanGetAUserWithoutPhoneNumber(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $newUser = $this->getUser($employer);
        $newUser->setPhoneNumber(null);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($newUser);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(16, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-email', $user->email);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt);
        self::assertSame('user-picture', $user->picture);
        self::assertSame('/employers/1', $user->employer);
        self::assertTrue($user->active);
        self::assertSame('2023-01-02T00:00:00+01:00', $user->connectedAt);
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }

    /**
     * Test qu'un utilisateur sans photo puisse être renvoyé.
     */
    public function testCanGetAUserWithoutPicture(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $newUser = $this->getUser($employer);
        $newUser->setPicture(null);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($newUser);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(16, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-email', $user->email);
        self::assertSame('user-phoneNumber', $user->phoneNumber);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt, 'createAt');
        self::assertSame('/employers/1', $user->employer);
        self::assertTrue($user->active);
        self::assertSame('2023-01-02T00:00:00+01:00', $user->connectedAt, 'connectedAt');
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }

    /**
     * Test qu'un utilisateur sans date de connexion
     * puisse être renvoyé.
     */
    public function testCanGetAUserWithoutConnectedAt(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $newUser = $this->getUser($employer);
        $newUser->setConnectedAt(null);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($newUser);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(16, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-email', $user->email);
        self::assertSame('user-phoneNumber', $user->phoneNumber);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt, 'createAt');
        self::assertSame('user-picture', $user->picture);
        self::assertSame('/employers/1', $user->employer);
        self::assertTrue($user->active);
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }

    /**
     * Test qu'un utilisateur inactif puisse être renvoyé.
     */
    public function testCanGetAnInactiveUser(): void
    {
        $client = static::createClient();

        $employer = $this->getEmployer();
        $newUser = $this->getUser($employer);
        $newUser->setActive(false);

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($employer);
        $entityManager->persist($newUser);
        $entityManager->flush();

        $apiResponse = $client->request('GET', '/users/1', ['auth_bearer' => $this->getJWT()]);

        self::assertSame(200, $apiResponse->getStatusCode(), 'GET "/users/1" failed.');
        self::assertJson($apiResponse->getContent());

        $user = json_decode($apiResponse->getContent(), false);

        self::assertCount(17, (array) $user, 'Too much data has been returned.');

        self::assertSame(1, $user->id);
        self::assertSame('user-login', $user->login);
        self::assertSame('user-password', $user->password);
        self::assertSame('user-firstname', $user->firstname);
        self::assertSame('user-lastname', $user->lastname);
        self::assertSame('user-email', $user->email);
        self::assertSame('user-phoneNumber', $user->phoneNumber);
        self::assertSame('2023-01-01T00:00:00+01:00', $user->createdAt, 'createAt');
        self::assertSame('user-picture', $user->picture);
        self::assertSame('/employers/1', $user->employer);
        self::assertFalse($user->active);
        self::assertSame('2023-01-02T00:00:00+01:00', $user->connectedAt, 'connectedAt');
        self::assertIsArray($user->groups);

        $atId = '@id';
        self::assertSame('/users/1', $user->$atId);
        $atType = '@type';
        self::assertSame('User', $user->$atType);
    }
}

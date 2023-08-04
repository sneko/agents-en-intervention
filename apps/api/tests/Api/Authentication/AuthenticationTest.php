<?php

declare(strict_types=1);

namespace App\Tests\Api\Authentication;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\PictureTag;
use App\Entity\Priority;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test l'authentification.
 */
#[
    PA\CoversClass(User::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Comment::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Intervention::class),
    PA\UsesClass(Location::class),
    PA\UsesClass(Picture::class),
    PA\UsesClass(PictureTag::class),
    PA\UsesClass(Priority::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(Status::class),
    PA\UsesClass(Type::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('authentication')
]
final class AuthenticationTest extends ApiTestCase
{
    // Méthodes :

    /**
     * Ajoute un utilisateur.
     */
    private function addUser(): void
    {
        $container = self::getContainer();

        $writeRole = new Role('ROLE_ALL_WRITE', null);
        $readRole = new Role('ROLE_ALL_READ', null);

        $adminGroup = new Group('admin', 'admin', null);
        $adminGroup->addRole($writeRole);
        $adminGroup->addRole($readRole);

        $electedGroup = new Group('user', 'user', null);
        $electedGroup->addRole($readRole);

        $employer = new Employer('employer-siren', 'employer-name');
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'user-picture',
            $employer
        );
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'user-password')
        );
        $user->addGroup($adminGroup);
        $user->addGroup($electedGroup);

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($writeRole);
        $manager->persist($readRole);
        $manager->persist($adminGroup);
        $manager->persist($electedGroup);
        $manager->persist($employer);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Test que l'authentification renvoie un JWT.
     */
    public function testCanReturnAJsonWebToken(): void
    {
        $client = static::createClient();

        $this->addUser();

        $apiResponse = $client->request('POST', '/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'login' => 'user-login',
                'password' => 'user-password',
            ],
         ]);

        $this->assertSame(200, $apiResponse->getStatusCode());

        $token = json_decode($apiResponse->getContent(), false);

        self::assertObjectHasProperty('token', $token);
    }

    /**
     * Test que l'on ne puisse pas s'authentifier
     * avec un identifiant invalide.
     */
    public function testCanNotAuthenticateWithAnInvalidLogin(): void
    {
        $client = static::createClient();

        $this->addUser();

        $apiResponse = $client->request('POST', '/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'login' => 'not-a-user-login',
                'password' => 'user-password',
            ],
         ]);

        $this->assertSame(401, $apiResponse->getStatusCode());
    }

    /**
     * Test que l'on ne puisse pas s'authentifier
     * avec un mot de passe invalide.
     */
    public function testCanNotAuthenticateWithAnInvalidPassword(): void
    {
        $client = static::createClient();

        $this->addUser();

        $apiResponse = $client->request('POST', '/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'login' => 'user-login',
                'password' => 'not-a-user-password',
            ],
         ]);

        $this->assertSame(401, $apiResponse->getStatusCode());
    }

    /**
     * Ajoute un utilisateur désactivé.
     */
    private function addADeactivatedUser(): void
    {
        $container = self::getContainer();

        $writeRole = new Role('ROLE_ALL_WRITE', null);
        $readRole = new Role('ROLE_ALL_READ', null);

        $adminGroup = new Group('admin', 'admin', null);
        $adminGroup->addRole($writeRole);
        $adminGroup->addRole($readRole);

        $electedGroup = new Group('user', 'user', null);
        $electedGroup->addRole($readRole);

        $employer = new Employer('employer-siren', 'employer-name');
        $user = new User(
            'user-login',
            'user-password',
            'user-firstname',
            'user-lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'user-picture',
            $employer,
            false
        );
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'user-password')
        );
        $user->addGroup($adminGroup);
        $user->addGroup($electedGroup);

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($writeRole);
        $manager->persist($readRole);
        $manager->persist($adminGroup);
        $manager->persist($electedGroup);
        $manager->persist($employer);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Test que l'on ne puisse pas s'authentifier
     * avec un compte désactivé.
     */
    public function testCanNotAuthenticateADeactivatedUser(): void
    {
        $client = static::createClient();

        $this->addADeactivatedUser();

        $apiResponse = $client->request('POST', '/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'login' => 'user-login',
                'password' => 'user-password',
            ],
         ]);

        $this->assertSame(401, $apiResponse->getStatusCode());
    }
}

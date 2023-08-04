<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;

abstract class AuthenticationTestCase extends ApiTestCase
{
    // Méthodes :

    /**
     * Ajoute un utilisateur.
     */
    private function addUser(): void
    {
        $container = self::getContainer();

        $allRole = new Role('ROLE_ALL', null);

        $adminGroup = new Group('all', 'all', null);
        $adminGroup->addRole($allRole);

        $employer = new Employer('siren', 'name');
        $user = new User(
            'login',
            'password',
            'firstname',
            'lastname',
            null,
            null,
            new \DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            'picture',
            $employer
        );
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'password')
        );
        $user->addGroup($adminGroup);

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($allRole);
        $manager->persist($adminGroup);
        $manager->persist($employer);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Renvoie le JWT.
     * @return string le JWT.
     */
    protected function getJWT(): string
    {
        $client = static::createClient();

        $this->addUser();

        $client->request('POST', '/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'login' => 'login',
                'password' => 'password',
            ],
        ]);
        $apiResponse = $client->getResponse()->getContent();
        $token = json_decode($apiResponse, false);

        return $token->token;
    }
}

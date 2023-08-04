<?php

declare(strict_types=1);

namespace App\Tests\Api\Comments;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Employer;
use App\Entity\Group;
use App\Entity\Intervention;
use App\Entity\Location;
use App\Entity\Priority;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Api\AuthenticationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use PHPUnit\Framework\Attributes as PA;

/**
 * Test POST /comments.
 */
#[
    PA\CoversClass(Comment::class),
    PA\UsesClass(Category::class),
    PA\UsesClass(Employer::class),
    PA\UsesClass(Group::class),
    PA\UsesClass(Intervention::class),
    PA\UsesClass(Location::class),
    PA\UsesClass(Priority::class),
    PA\UsesClass(Role::class),
    PA\UsesClass(Status::class),
    PA\UsesClass(Type::class),
    PA\UsesClass(User::class),
    PA\UsesClass(UserRepository::class),
    PA\Group('api'),
    PA\Group('api_comments'),
    PA\Group('api_comments_post'),
    PA\Group('comment')
]
final class CommentPostTest extends AuthenticationTestCase
{
    // Méthodes :

    /**
     * Test que la route nécessite d'être authentifié.
     */
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();

        $apiResponse = $client->request('POST', '/comments');

        self::assertSame(401, $apiResponse->getStatusCode(), 'POST "/comments" succeeded.');
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


        $locationSet = $loader->loadFile(__DIR__ . '/../../../fixtures/location.yaml');

        foreach ($locationSet->getObjects() as $location) {
            $entityManager->persist($location);
        }


        $prioritySet = $loader->loadFile(__DIR__ . '/../../../fixtures/priority.yaml');

        foreach ($prioritySet->getObjects() as $priority) {
            $entityManager->persist($priority);
        }


        $typeSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/type.yaml',
            $categorySet->getParameters(),
            $categorySet->getObjects()
        );

        foreach ($typeSet->getObjects() as $type) {
            $entityManager->persist($type);
        }


        $statusSet = $loader->loadFile(__DIR__ . '/../../../fixtures/status.yaml');

        foreach ($statusSet->getObjects() as $status) {
            $entityManager->persist($status);
        }


        $employerSet = $loader->loadFile(__DIR__ . '/../../../fixtures/employer.yaml');

        foreach ($employerSet->getObjects() as $employer) {
            $entityManager->persist($employer);
        }


        $roleSet = $loader->loadFile(__DIR__ . '/../../../fixtures/role.yaml');

        foreach ($roleSet->getObjects() as $role) {
            $entityManager->persist($role);
        }


        $groupSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/group.yaml',
            $roleSet->getParameters(),
            $roleSet->getObjects()
        );

        foreach ($groupSet->getObjects() as $group) {
            $entityManager->persist($group);
        }


        $userSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/user.yaml',
            array_merge(
                $employerSet->getParameters(),
                $groupSet->getParameters()
            ),
            array_merge(
                $employerSet->getObjects(),
                $groupSet->getObjects()
            )
        );

        foreach ($userSet->getObjects() as $user) {
            $entityManager->persist($user);
        }


        $interventionSet = $loader->loadFile(
            __DIR__ . '/../../../fixtures/intervention.yaml',
            array_merge(
                $statusSet->getParameters(),
                $prioritySet->getParameters(),
                $categorySet->getParameters(),
                $typeSet->getParameters(),
                $userSet->getParameters(),
                $employerSet->getParameters(),
                $locationSet->getParameters()
            ),
            array_merge(
                $statusSet->getObjects(),
                $prioritySet->getObjects(),
                $categorySet->getObjects(),
                $typeSet->getObjects(),
                $userSet->getObjects(),
                $employerSet->getObjects(),
                $locationSet->getObjects()
            )
        );

        foreach ($interventionSet->getObjects() as $intervention) {
            $entityManager->persist($intervention);
        }
        $entityManager->flush();
    }

    /**
     * Renvoie les données du commentaire.
     * @return array Les données du commentaire.
     */
    private function commentToPost(): array
    {
        return [
            'message' => 'comment-message',
            'createdAt' => '2023-01-01T00:00:00+0000',
            'author' => '/users/1',
            'intervention' => '/interventions/1'
        ];
    }

    /**
     * Test que des commentaires puissent être enregistrés.
     */
    public function testCanPostComments(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $apiResponse = $client->request(
            'POST',
            '/comments',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $this->commentToPost()
            ]
        );

        self::assertSame(201, $apiResponse->getStatusCode(), 'POST to "/comments" failed.');
        self::assertJson($apiResponse->getContent());

        $jsonResponse = json_decode($apiResponse->getContent(), false);

        self::assertSame(1, $jsonResponse->id);
        $atId = '@id';
        self::assertSame('/comments/1', $jsonResponse->$atId);
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * un commentaire avec un auteur inconnu.
     */
    public function testCanNotPostACommentFromANonExistantAuthor(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $commentDatas = $this->commentToPost();
        $commentDatas['author'] = '/users/999';

        $apiResponse = $client->request(
            'POST',
            '/comments',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $commentDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/comments" did not failed.');
    }


    /**
     * Test que l'on ne puisse pas ajouter
     * un commentaire avec une intervention inconnue.
     */
    public function testCanNotPostACommentWithANonExistantIntervention(): void
    {
        $client = static::createClient();

        $doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->addFixtures($doctrine->getManager());

        $commentDatas = $this->commentToPost();
        $commentDatas['intervention'] = '/interventions/999';

        $apiResponse = $client->request(
            'POST',
            '/comments',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'application/json',
                    'authorization' => 'Bearer ' . $this->getJWT()
                ],
                'json' => $commentDatas
            ]
        );

        self::assertSame(400, $apiResponse->getStatusCode(), 'POST to "/comments" did not failed.');
    }
}

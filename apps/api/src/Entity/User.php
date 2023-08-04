<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un utilisateur.
 */
#[
    ApiFilter(SearchFilter::class, properties: [
        'active' => 'exact',
        'groups.slug' => 'exact'
    ]),
    Get(
        normalizationContext: ['groups' => ['user:get']],
        security: 'is_granted("VIEW", object)'
    ),
    GetCollection(
        normalizationContext: ['groups' => ['agent:get-collection']],
        uriTemplate: '/employers/{employerId}/active-agents',
        uriVariables: [
            'employerId' => new Link(fromClass: User::class, toProperty: 'employer'),
        ],
        security: 'is_granted("ROLE_GET_ACTIVE_AGENTS")'
    ),
    GetCollection(
        normalizationContext: ['groups' => ['user:get-collection']],
        uriTemplate: '/employers/{employerId}/users',
        uriVariables: [
            'employerId' => new Link(fromClass: User::class, toProperty: 'employer'),
        ],
        security: 'is_granted("ROLE_GET_USERS")'
    ),
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: '`user`')
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        Groups([
            'agent:get-collection',
            'intervention:get',
            'user:get-collection'
        ]),
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column
    ]
    private ?int $id;

    /**
     * @var string l'identifiant de connexion.
     */
    #[
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $login;

    /**
     * @var string le mot de passe.
     */
    #[
        ORM\Column(type: Types::TEXT)
    ]
    private string $password;

    /**
     * @var string le prénom.
     */
    #[
        Groups([
            'agent:get-collection',
            'intervention:get',
            'intervention:get-collection',
            'user:get',
            'user:get-collection'
        ]),
        ORM\Column(type: Types::TEXT)
    ]
    private string $firstname;

    /**
     * @var string le nom.
     */
    #[
        Groups([
            'agent:get-collection',
            'intervention:get',
            'intervention:get-collection',
            'user:get',
            'user:get-collection'
        ]),
        ORM\Column(type: Types::TEXT)
    ]
    private string $lastname;

    /**
     * @var string|null l'e-mail.
     */
    #[
        Groups(['user:get-collection']),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $email;

    /**
     * @var string|null le numéro de téléphone.
     */
    #[
        Groups(['user:get-collection']),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $phoneNumber;

    /**
     * @var \DateTimeImmutable la date de création.
     */
    #[ORM\Column()]
    private \DateTimeImmutable $createdAt;

    /**
     * @var string|null la photo.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'user:get',
            'user:get-collection'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $picture;

    /**
     * @var \App\Entity\Employer l'employeur.
     */
    #[
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Employer $employer;

    /**
     * @var bool si le compte est actif.
     */
    #[
        ORM\Column(
            options: [
                "default" => true
            ]
        )
    ]
    private bool $active;

    /**
     * @var \DateTimeImmutable|null la date de la dernière connexion.
     */
    #[
        ORM\Column(
            nullable: true,
            options: [
                "default" => null
            ]
        )
    ]
    private ?\DateTimeImmutable $connectedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection les groupes.
     */
    #[
        Groups(['user:get-collection']),
        ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    private Collection $groups;

    /**
     * @var string[] les rôles.
     */
    private array $roles;



    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $login l'identifiant de connexion.
     * @param string $password le mot de passe.
     * @param string $firstname le prénom.
     * @param string $lastname le nom.
     * @param string|null $email l'e-mail.
     * @param string|null $phoneNumber le numéro de téléphone.
     * @param \DateTimeImmutable $createdAt la date de création.
     * @param string|null $picture la photo.
     * @param \App\Entity\Employer $employer l'employeur.
     * @param bool $active si le compte est actif.
     * @param \DateTimeImmutable|null $connectedAt la date de la dernière connexion.
     */
    public function __construct(
        string $login,
        string $password,
        string $firstname,
        string $lastname,
        ?string $email,
        ?string $phoneNumber,
        \DateTimeImmutable $createdAt,
        ?string $picture,
        Employer $employer,
        bool $active = true,
        ?\DateTimeImmutable $connectedAt = null
    ) {
        $this->id = null;
        $this->login = $login;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->createdAt = $createdAt;
        $this->picture = $picture;
        $this->employer = $employer;
        $this->active = $active;
        $this->connectedAt = $connectedAt;
        $this->groups = new ArrayCollection();
        $this->roles = [];
    }


    // Accesseurs :

    /**
     * Renvoie l'identifiant.
     * @return int|null l'identifiant.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Renvoie l'identifiant de connexion.
     * @return string l'identifiant de connexion.
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Renvoie le mot de passe.
     * @return string le mot de passe.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Renvoie le prénom.
     * @return string le prénom.
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Renvoie le nom.
     * @return string le nom.
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Renvoie l'e-mail.
     * @return string|null l'e-mail.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Renvoie le numéro de téléphone.
     * @return string|null le numéro de téléphone.
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * Renvoie la date de création.
     * @return \DateTimeImmutable la date de création.
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Renvoie si la photo.
     * @return string|null la photo.
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * Renvoie l'employeur.
     * @return \App\Entity\Employer l'employeur.
     */
    public function getEmployer(): Employer
    {
        return $this->employer;
    }

    /**
     * Renvoie si le compte est actif.
     * @return bool si le compte est actif.
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Renvoie la date de la dernière connexion.
     * @return \DateTimeImmutable|null la date de la dernière connexion.
     */
    public function getConnectedAt(): ?\DateTimeImmutable
    {
        return $this->connectedAt;
    }

    /**
     * Renvoie les groupes.
     * @return \Doctrine\Common\Collections\Collection les groupes.
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }


    // Mutateurs ;

    /**
     * Change l'identifiant de connexion.
     * @param string $login l'identifiant de connexion.
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * Change le mot de passe.
     * @param string $password le mot de passe.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Change le prénom.
     * @param string $firstname le prénom.
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Change le nom.
     * @param string $lastname le nom.
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * Change l'e-mail.
     * @param string|null $email l'e-mail.
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Change le numéro de téléphone.
     * @param string|null $phoneNumber le numéro de téléphone.
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Change la date de création.
     * @param \DateTimeImmutable $createdAt la date de création.
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Change la photo.
     * @param string|null $picture la photo.
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * Change l'employeur.
     * @param \App\Entity\Employer $employer l'employeur.
     */
    public function setEmployer(Employer $employer): void
    {
        $this->employer = $employer;
    }

    /**
     * Change si le compte est actif.
     * @param bool $active si le compte est actif.
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Change la date de la dernière connexion.
     * @param \DateTimeImmutable|null $connectedAt la date de la dernière connexion.
     */
    public function setConnectedAt(?\DateTimeImmutable $connectedAt): void
    {
        $this->connectedAt = $connectedAt;
    }


    // Collections :

    /**
     * Ajoute un groupe.
     * @param \App\Entity\Group $group un groupe.
     */
    public function addGroup(Group $group): void
    {
        if ($this->groups->contains($group) === false) {
            $this->groups->add($group);
        }
    }

    /**
     * Enlève un groupe.
     * @param \App\Entity\Group $group un groupe.
     */
    public function removeGroup(Group $group): void
    {
        $this->groups->removeElement($group);
    }


    // UserInterface :

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Change les rôles.
     * @param string[] $roles les rôles.
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }
}

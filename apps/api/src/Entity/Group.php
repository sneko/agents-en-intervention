<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un groupe.
 */
#[
    ORM\Entity(),
    ORM\Table(name: '`group`')
]
class Group
{
    // Constantes :

    /**
     * Le groupe agent.
     */
    public const AGENT = 'agent';


    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column
    ]
    private ?int $id;

    /**
     * @var string le nom.
     */
    #[
        Groups(['user:get-collection']),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $name;

    /**
     * @var string le slug.
     */
    #[
        Groups(['user:get-collection']),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $slug;

    /**
     * @var string|null la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    /**
     * @var \Doctrine\Common\Collections\Collection les utilisateurs.
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groups')]
    private Collection $users;

    /**
     * @var \Doctrine\Common\Collections\Collection les rôles.
     */
    #[ORM\ManyToMany(targetEntity: Role::class)]
    private Collection $roles;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $name le nom.
     * @param string $slug le slug.
     * @param string|null $description la description.
     */
    public function __construct(
        string $name,
        string $slug,
        ?string $description
    ) {
        $this->id = null;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->users = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * Renvoie le nom.
     * @return string le nom.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Renvoie le slug.
     * @return string le slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Renvoie la description.
     * @return string|null la description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Renvoie les utilisateurs.
     * @return \Doctrine\Common\Collections\Collection les utilisateurs.
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Renvoie les rôles.
     * @return \Doctrine\Common\Collections\Collection les rôles.
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }


    // Mutateurs :

    /**
     * Change le nom.
     * @param string $name le nom.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Change le slug
     * @param string $slug le slug.
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Change la description.
     * @param string|null $description la description.
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    // Collections :

    /**
     * Ajoute un utilisateur.
     * @param \App\Entity\User $user un utilisateur.
     */
    public function addUser(User $user): void
    {
        if ($this->users->contains($user) === false) {
            $this->users->add($user);
        }
    }

    /**
     * Enlève un utilisateur.
     * @param \App\Entity\User $user un utilisateur.
     */
    public function removeUser(User $user): void
    {
        $this->users->removeElement($user);
    }

    /**
     * Ajoute un rôle.
     * @param \App\Entity\Role $role un rôle.
     */
    public function addRole(Role $role): void
    {
        if ($this->roles->contains($role) === false) {
            $this->roles->add($role);
        }
    }

    /**
     * Enlève un rôle.
     * @param \App\Entity\Role $role un rôle.
     */
    public function removeRole(Role $role): void
    {
        $this->roles->removeElement($role);
    }
}

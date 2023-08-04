<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un employeur.
 */
#[
    Get(
        normalizationContext: ['groups' => ['employer:get']],
        security: 'is_granted("VIEW", object)'
    ),
    ORM\Entity()
]
class Employer
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        Groups(['employer:get']),
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column
    ]
    private ?int $id;

    /**
     * @var string le numéro SIREN.
     */
    #[
        Groups(['employer:get']),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $siren;

    /**
     * @var string le prénom.
     */
    #[
        Groups(['employer:get']),
        ORM\Column(type: Types::TEXT)
    ]
    private string $name;

    /**
     * @var float la longitude.
     */
    #[
        Assert\Range(
            min: -180,
            max: 180,
            notInRangeMessage: 'employer.longitude.notInRange'
        ),
        Groups(['employer:get']),
        ORM\Column(type: Types::DECIMAL, precision: 9, scale: 6)
    ]
    private float $longitude;

    /**
     * @var float la latitude.
     */
    #[
        Assert\Range(
            min: -90,
            max: 90,
            notInRangeMessage: 'employer.latitude.notInRange'
        ),
        Groups(['employer:get']),
        ORM\Column(type: Types::DECIMAL, precision: 8, scale: 6)
    ]
    private float $latitude;

    /**
     * @var \Doctrine\Common\Collections\Collection les utilisateurs.
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'employer')]
    private Collection $users;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $siren le numéro SIREN.
     * @param string $name le prénom.
     * @param float $longitude la longitude.
     * @param float $latitude la latitude.
     */
    public function __construct(
        string $siren,
        string $name,
        float $longitude,
        float $latitude
    ) {
        $this->id = null;
        $this->siren = $siren;
        $this->name = $name;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->users = new ArrayCollection();
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
     * Renvoie le numéro SIREN.
     * @return string le numéro SIREN.
     */
    public function getSiren(): string
    {
        return $this->siren;
    }

    /**
     * Renvoie le prénom.
     * @return string le prénom.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Renvoie la longitude.
     * @return float la longitude.
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Renvoie la latitude.
     * @return float la latitude.
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Renvoie les utilisateurs.
     * @return \Doctrine\Common\Collections\Collection les utilisateurs.
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    // Mutateurs ;

    /**
     * Change le numéro SIREN.
     * @param string $siren le numéro SIREN.
     */
    public function setSiren(string $siren): void
    {
        $this->siren = $siren;
    }

    /**
     * Change le prénom.
     * @param string $name le prénom.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Change la longitude.
     * @param float $longitude la longitude.
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * Change la latitude.
     * @param float $latitude la latitude.
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
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
}

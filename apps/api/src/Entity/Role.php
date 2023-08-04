<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * L'entité représentant un rôle.
 */
#[ORM\Entity()]
class Role
{
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
    #[ORM\Column(type: Types::TEXT, unique: true)]
    private string $name;

    /**
     * @var string|null la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $name le nom.
     * @param string|null $description la description.
     */
    public function __construct(
        string $name,
        ?string $description
    ) {
        $this->id = null;
        $this->name = $name;
        $this->description = $description;
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
     * Renvoie la description.
     * @return string|null la description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * Change la description.
     * @param string|null $description la description.
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * L'entité représentant un type d'action.
 */
#[
    ORM\Entity(),
    ORM\UniqueConstraint(columns: ['name', 'type_id']),
    UniqueEntity(['name', 'type'])
]
class ActionType
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column()
    ]
    private ?int $id;

    /**
     * @var string le nom.
     */
    #[ORM\Column(type: Types::TEXT)]
    private string $name;

    /**
     * @var string|null la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    /**
     * @var \App\Entity\Type le type.
     */
    #[
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne(inversedBy: 'actionTypes')
    ]
    private Type $type;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $name le nom.
     * @param string|null $description la description.
     * @param \App\Entity\Type $type le type.
     */
    public function __construct(
        string $name,
        ?string $description,
        Type $type
    ) {
        $this->id = null;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
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

    /**
     * Renvoie le type.
     * @return \App\Entity\Type le type.
     */
    public function getType(): Type
    {
        return $this->type;
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

    /**
     * Change le type.
     * @param \App\Entity\Type $type le type.
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
    }
}

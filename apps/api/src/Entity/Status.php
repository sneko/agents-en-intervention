<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un statut.
 */
#[
    Get('status/{slug}'),
    GetCollection('status'),
    ORM\Entity()
]
class Status
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        ApiProperty(identifier: false),
        Groups(['intervention:get']),
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column
    ]
    private ?int $id;

    /**
     * @var string le nom.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $name;

    /**
     * @var string le slug.
     */
    #[
        ApiProperty(identifier: true),
        Groups(['intervention:get-collection']),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $slug;

    /**
     * @var string|null la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;


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
}

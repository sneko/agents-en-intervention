<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant une priorité.
 */
#[
    GetCollection(),
    ORM\Entity()
]
class Priority
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
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
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $slug;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $name le nom.
     * @param string $slug le slug.
     */
    public function __construct(string $name, string $slug)
    {
        $this->id = null;
        $this->name = $name;
        $this->slug = $slug;
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
}

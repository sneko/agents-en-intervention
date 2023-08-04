<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un type.
 */
#[
    GetCollection(paginationEnabled: false),
    ORM\Entity(),
    ORM\UniqueConstraint(columns: ['name', 'category_id']),
    UniqueEntity(['name', 'category'])
]
class Type
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
        Groups(['intervention:get']),
        ORM\Column(type: Types::TEXT)
    ]
    private string $name;

    /**
     * @var string|null la description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    /**
     * @var string|null la photo.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $picture;

    /**
     * @var \App\Entity\Category la catégorie.
     */
    #[
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne(inversedBy: 'types')
    ]
    private Category $category;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $name le nom.
     * @param string|null $description la description.
     * @param string|null $picture la photo.
     * @param \App\Entity\Category $category la catégorie.
     */
    public function __construct(
        string $name,
        ?string $description,
        ?string $picture,
        Category $category
    ) {
        $this->id = null;
        $this->name = $name;
        $this->description = $description;
        $this->picture = $picture;
        $this->category = $category;
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
     * Renvoie si la photo.
     * @return string|null la photo.
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * Renvoie la catégorie.
     * @return \App\Entity\Category la catégorie.
     */
    public function getCategory(): Category
    {
        return $this->category;
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
     * Change la photo.
     * @param string|null $picture la photo.
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * Change la catégorie.
     * @param \App\Entity\Category $category la catégorie.
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}

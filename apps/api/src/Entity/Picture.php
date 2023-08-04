<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant une photo.
 */
#[
    Delete(),
    ORM\Entity(),
    Post(
        inputFormats: ['multipart' => ['multipart/form-data']],
        denormalizationContext: ['groups' => ['picture:post']],
    )
]
class Picture
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column(type: Types::BIGINT)
    ]
    private ?int $id;

    /**
     * @var string le nom du fichier.
     */
    #[
        Groups(['picture:post']),
        ORM\Column(type: Types::TEXT, unique: true)
    ]
    private string $fileName;

    /**
     * @var string|null l'URL.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
    ]
    private ?string $URL;

    /**
     * @var \App\Entity\Intervention l'intervention.
     */
    #[
        Groups(['picture:post']),
        ORM\JoinColumn(
            nullable: false,
            onDelete: "cascade"
        ),
        ORM\ManyToOne(inversedBy: 'pictures')
    ]
    private Intervention $intervention;

    /**
     * @var \DateTimeImmutable la date de création.
     */
    #[
        Groups(['picture:post']),
        ORM\Column()
    ]
    private \DateTimeImmutable $createdAt;

    /**
     * @var \Doctrine\Common\Collections\Collection les tags.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'picture:post'
        ]),
        ORM\ManyToMany(targetEntity: PictureTag::class)
    ]
    private Collection $tags;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $fileName le nom du fichier.
     * @param \App\Entity\Intervention $intervention l'intervention.
     * @param \DateTimeImmutable $createdAt la date de création.
     * @param string|null $URL l'URL du fichier.
     */
    public function __construct(
        string $fileName,
        Intervention $intervention,
        \DateTimeImmutable $createdAt,
        ?string $URL = null
    ) {
        $this->id = null;
        $this->fileName = $fileName;
        $this->URL = $URL;
        $this->intervention = $intervention;
        $this->createdAt = $createdAt;
        $this->tags = new ArrayCollection();
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
     * Renvoie le fichier.
     * @param string le fichier.
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Renvoie l'intervention.
     * @return \App\Entity\Intervention l'intervention.
     */
    public function getIntervention(): Intervention
    {
        return $this->intervention;
    }

    /**
     * Renvoie l'intervention.
     * @return \DateTimeImmutable la date de création.
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Renvoie l'URL.
     * @param string|null l'URL.
     */
    public function getURL(): ?string
    {
        return $this->URL;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection les tags.
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }


    // Mutateurs :

    /**
     * Change le nom du fichier.
     * @param string $fileName le nom du fichier.
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * Change l'intervention.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    public function setIntervention(Intervention $intervention): void
    {
        $this->intervention = $intervention;
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
     * Change l'URL.
     * @param string|null $URL l'URL.
     */
    public function setURL(?string $URL): void
    {
        $this->URL = $URL;
    }


    // Collections :

    /**
     * Ajoute un tag.
     * @param \App\Entity\PictureTag $tag un tag.
     */
    public function addTag(PictureTag $tag): void
    {
        if ($this->tags->contains($tag) === false) {
            $this->tags->add($tag);
        }
    }

    /**
     * Enlève un tag.
     * @param \App\Entity\PictureTag $tag un tag.
     */
    public function removeTag(PictureTag $tag): void
    {
        $this->tags->removeElement($tag);
    }
}

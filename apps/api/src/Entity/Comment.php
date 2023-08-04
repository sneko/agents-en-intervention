<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité représentant un commentaire.
 */
#[
    ORM\Entity(),
    Post(denormalizationContext: ['groups' => ['comment:post']])
]
class Comment
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
     * @var string le message.
     */
    #[
        Groups([
            'comment:post',
            'intervention:get'
        ]),
        ORM\Column(type: Types::TEXT)
    ]
    private string $message;

    /**
     * @var \DateTimeImmutable la date de création.
     */
    #[
        Groups([
            'comment:post',
            'intervention:get'
        ]),
        ORM\Column
    ]
    private \DateTimeImmutable $createdAt;

    /**
     * @var \App\Entity\User l'auteur.
     */
    #[
        Groups([
            'comment:post',
            'intervention:get'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private User $author;

    /**
     * @var \App\Entity\Intervention l'intervention.
     */
    #[
        Groups(['comment:post']),
        ORM\JoinColumn(
            nullable: false,
            onDelete: "cascade"
        ),
        ORM\ManyToOne(inversedBy: 'comments')
    ]
    private Intervention $intervention;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $message le message.
     * @param \DateTimeImmutable $createdAt la date de création.
     * @param \App\Entity\User $author l'auteur.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    public function __construct(
        string $message,
        \DateTimeImmutable $createdAt,
        User $author,
        Intervention $intervention
    ) {
        $this->id = null;
        $this->message = $message;
        $this->createdAt = $createdAt;
        $this->author = $author;
        $this->intervention = $intervention;
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
     * Renvoie le message.
     * @return string le message.
     */
    public function getMessage(): string
    {
        return $this->message;
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
     * Renvoie l'auteur.
     * @return \App\Entity\User l'auteur.
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * Renvoie l'intervention.
     * @return \App\Entity\Intervention l'intervention.
     */
    public function getIntervention(): Intervention
    {
        return $this->intervention;
    }


    // Mutateurs :

    /**
     * Change le message.
     * @param string $message le message.
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
     * Change l'auteur.
     * @param \App\Entity\User $author l'auteur.
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * Change l'intervention.
     * @param \App\Entity\Intervention $intervention l'intervention.
     */
    public function setIntervention(Intervention $intervention): void
    {
        $this->intervention = $intervention;
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\DeleteInterventionProcessor;
use App\State\InterventionProvider;
use App\State\EmployerInterventionsProvider;
use App\State\UserInterventionsProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * L'entité représentant une intervention.
 */
#[
    ApiFilter(OrderFilter::class, properties: ['id']),
    ApiFilter(SearchFilter::class, properties: ['status.slug' => 'exact']),
    Delete(
        processor: DeleteInterventionProcessor::class,
        security: 'is_granted("ROLE_DELETE_INTERVENTION") and is_granted("DELETE", object)'
    ),
    Get(
        normalizationContext: ['groups' => ['intervention:get']],
        security: 'is_granted("VIEW", object)',
        provider: InterventionProvider::class
    ),
    GetCollection(
        normalizationContext: ['groups' => ['intervention:get-collection']],
        uriTemplate: '/employers/{employerId}/interventions',
        uriVariables: [
            'employerId' => new Link(fromClass: Intervention::class, toProperty: 'employer'),
        ],
        security: 'is_granted("ROLE_READ_EMPLOYER_INTERVENTIONS")',
        provider: EmployerInterventionsProvider::class
    ),
    GetCollection(
        normalizationContext: ['groups' => ['intervention:get-collection']],
        uriTemplate: '/users/{userId}/interventions',
        uriVariables: [
            'userId' => new Link(fromClass: Intervention::class, toProperty: 'participants'),
        ],
        security: 'is_granted("ROLE_READ_ASSIGNED_TO_ME_INTERVENTIONS")',
        provider: UserInterventionsProvider::class
    ),
    Patch(
        denormalizationContext: ['groups' => ['intervention:patch']],
        output: false,
        status: Response::HTTP_NO_CONTENT
    ),
    Post(
        denormalizationContext: ['groups' => ['intervention:post']]
    ),
    ORM\Entity()
]
class Intervention
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column(type: Types::BIGINT)
    ]
    private ?int $id;

    /**
     * @var \DateTimeImmutable la date de création.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:post'
        ]),
        ORM\Column()
    ]
    private \DateTimeImmutable $createdAt;

    /**
     * @var string|null la description
     */
    #[
        Groups([
            'intervention:get',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $description;

    /**
     * @var \App\Entity\Status le statut.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Status $status;

    /**
     * @var \App\Entity\Priority la priorité.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Priority $priority;

    /**
     * @var \App\Entity\Category la catégorie.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Category $category;

    /**
     * @var \App\Entity\Type le type.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Type $type;

    /**
     * @var \App\Entity\User l'auteur.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private User $author;

    /**
     * @var \App\Entity\Employer l'employeur.
     */
    #[
        Groups([
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private Employer $employer;

    /**
     * @var \App\Entity\Location la localisation.
     */
    #[
        Assert\Valid(),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne(cascade: ['persist'])
    ]
    private Location $location;

    /**
     * @var \Doctrine\Common\Collections\Collection les intervenants.
     */
    #[
        /**
         * Les groupes "intervention:patch" et "intervention:post"
         * sont ajoutés dynamiquement.
         */
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch', // Utiliser l'ajout dynamique du groupe.
            'intervention:post' // Utiliser l'ajout dynamique du groupe.
        ]),
        ORM\ManyToMany(targetEntity: User::class)
    ]
    private Collection $participants;

    /**
     * @var \Doctrine\Common\Collections\Collection les commentaires.
     */
    #[
        Groups(['intervention:get']),
        ORM\OneToMany(mappedBy: 'intervention', targetEntity: Comment::class)
    ]
    private Collection $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection les photos.
     */
    #[
        Groups([
            'intervention:get',
            'intervention:get-collection'
        ]),
        ORM\OneToMany(
            mappedBy: 'intervention',
            targetEntity: Picture::class,
            cascade: ['persist']
        )
    ]
    private Collection $pictures;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param \DateTimeImmutable $createdAt la date de création.
     * @param string|null $description la description.
     * @param \App\Entity\Status $status le statut.
     * @param \App\Entity\Priority $priority la priorité.
     * @param \App\Entity\Category $category la catégorie.
     * @param \App\Entity\Type $type le type.
     * @param \App\Entity\User $author l'auteur.
     * @param \App\Entity\Employer $employer l'employeur.
     * @param \App\Entity\Location $location la localisation.
     */
    public function __construct(
        \DateTimeImmutable $createdAt,
        ?string $description,
        Status $status,
        Priority $priority,
        Category $category,
        Type $type,
        User $author,
        Employer $employer,
        Location $location
    ) {
        $this->id = null;
        $this->createdAt = $createdAt;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->category = $category;
        $this->type = $type;
        $this->author = $author;
        $this->employer = $employer;
        $this->location = $location;
        $this->participants = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->pictures = new ArrayCollection();
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
     * Renvoie la date de création.
     * @return \DateTimeImmutable la date de création.
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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
     * Renvoie le statut.
     * @return \App\Entity\Status le statut.
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Renvoie la priorité.
     * @return \App\Entity\Priority la priorité.
     */
    public function getPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * Renvoie la catégorie.
     * @return \App\Entity\Category la catégorie.
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * Renvoie le type.
     * @return \App\Entity\Type le type.
     */
    public function getType(): Type
    {
        return $this->type;
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
     * Renvoie l'employeur.
     * @return \App\Entity\Employer l'employeur.
     */
    public function getEmployer(): Employer
    {
        return $this->employer;
    }

    /**
     * Renvoie la localisation.
     * @return \App\Entity\Location la localisation.
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * Renvoie les intervenants.
     * @return \Doctrine\Common\Collections\Collection les intervenants.
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * Renvoie les commentaires.
     * @return \Doctrine\Common\Collections\Collection les commentaires.
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Renvoie les photos.
     * @return \Doctrine\Common\Collections\Collection les photos.
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }


    // Mutateurs :

    /**
     * Change la date de création.
     * @param \DateTimeImmutable $createdAt la date de création.
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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
     * Change le statut.
     * @param \App\Entity\Status $status le statut.
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Change la priorité.
     * @param \App\Entity\Priority $priority la priorité.
     */
    public function setPriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Change la catégorie.
     * @param \App\Entity\Category $category la catégorie.
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Change le type.
     * @param \App\Entity\Type $type le type.
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
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
     * Change l'employeur.
     * @param \App\Entity\Employer $employer l'employeur.
     */
    public function setEmployer(Employer $employer): void
    {
        $this->employer = $employer;
    }

    /**
     * Change la localisation.
     * @param \App\Entity\Location $location la localisation.
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }


    // Collections :

    /**
     * Ajoute un participant.
     * @param \App\Entity\User $participant un participant.
     */
    public function addParticipant(User $participant): void
    {
        if ($this->participants->contains($participant) === false) {
            $this->participants->add($participant);
        }
    }

    /**
     * Enlève un participant.
     * @param \App\Entity\User $participant un participant.
     */
    public function removeParticipant(User $participant): void
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Ajoute un commentaire.
     * @param \App\Entity\Comment $comment un commentaire.
     */
    public function addComment(Comment $comment): void
    {
        if ($this->comments->contains($comment) === false) {
            $this->comments->add($comment);
        }
    }

    /**
     * Enlève un commentaire.
     * @param \App\Entity\Comment $comment un commentaire.
     */
    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Ajoute une photo.
     * @param \App\Entity\Picture $picture une photo.
     */
    public function addPicture(Picture $picture): void
    {
        if ($this->pictures->contains($picture) === false) {
            $this->pictures->add($picture);
        }
    }

    /**
     * Enlève une photo.
     * @param \App\Entity\Picture $picture une photo.
     */
    public function removePicture(Picture $picture): void
    {
        $this->pictures->removeElement($picture);
    }
}

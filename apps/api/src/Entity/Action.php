<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * L'entité représentant une action.
 */
#[ORM\Entity()]
class Action
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
     * @var \DateTimeImmutable la date de début.
     */
    #[ORM\Column()]
    private \DateTimeImmutable $beginAt;

    /**
     * @var \DateTimeImmutable|null la date de fin.
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt;

    /**
     * @var \App\Entity\ActionType le type d'action.
     */
    #[
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private ActionType $actionType;

    /**
     * @var \App\Entity\Intervention l'intervention.
     */
    #[
        ORM\JoinColumn(
            nullable: false,
            onDelete: "cascade"
        ),
        ORM\ManyToOne()
    ]
    private Intervention $intervention;

    /**
     * @var \App\Entity\User l'intervenant.
     */
    #[
        ORM\JoinColumn(nullable: false),
        ORM\ManyToOne()
    ]
    private User $participant;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param \DateTimeImmutable $beginAt la date de début.
     * @param \DateTimeImmutable|null $endAt la date de fin.
     * @param \App\Entity\ActionType $actionType le type d'action.
     * @param \App\Entity\Intervention $intervention l'intervention.
     * @param \App\Entity\User $participant l'intervenant.
     */
    public function __construct(
        \DateTimeImmutable $beginAt,
        ?\DateTimeImmutable $endAt,
        ActionType $actionType,
        Intervention $intervention,
        User $participant
    ) {
        $this->id = null;
        $this->beginAt = $beginAt;
        $this->endAt = $endAt;
        $this->actionType = $actionType;
        $this->intervention = $intervention;
        $this->participant = $participant;
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
     * Renvoie la date de début.
     * @return \DateTimeImmutable la date de début.
     */
    public function getBeginAt(): \DateTimeImmutable
    {
        return $this->beginAt;
    }

    /**
     * Renvoie la date de fin.
     * @return \DateTimeImmutable la date de fin.
     */
    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    /**
     * Renvoie le type d'action.
     * @return \App\Entity\ActionType le type d'action.
     */
    public function getActionType(): ActionType
    {
        return $this->actionType;
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
     * Renvoie l'intervenant.
     * @return \App\Entity\User l'intervenant.
     */
    public function getParticipant(): User
    {
        return $this->participant;
    }


    // Mutateurs :

    /**
     * Change la date de début.
     * @param \DateTimeImmutable $beginAt la date de début.
     */
    public function setBeginAt(\DateTimeImmutable $beginAt): void
    {
        $this->beginAt = $beginAt;
    }

    /**
     * Change la date de fin.
     * @param \DateTimeImmutable|null  $endAt la date de fin.
     */
    public function setEndAt(?\DateTimeImmutable $endAt): void
    {
        $this->endAt = $endAt;
    }

    /**
     * Change le type d'action.
     * @param \App\Entity\ActionType $actionType le type d'action.
     */
    public function setActionType(ActionType $actionType): void
    {
        $this->actionType = $actionType;
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
     * Change l'intervenant.
     * @param \App\Entity\User $participant l'intervenant.
     */
    public function setParticipant(User $participant): void
    {
        $this->participant = $participant;
    }
}

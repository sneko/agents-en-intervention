<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Employer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Le voteur pour les employeurs.
 */
class EmployerVoter extends Voter
{
    // Constantes :

    /**
     * La possibilité de voir les données.
     */
    const VIEW = 'VIEW';


    // Méthodes :

    /**
     * Détermine si ce voteur doit voter.
     * @param string $attribute l'attribut.
     * @param mixed $subject l'objet.
     * @return bool si ce voteur doit voter.
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (
            $attribute !== self::VIEW
            || ($subject instanceof Employer) === false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Détermine si l'utilisateur a ce droit sur l'objet.
     * @param string $attribute l'attribut.
     * @param mixed $subject l'objet.
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token le jeton.
     * @return bool si l'utilisateur a ce droit sur l'objet.
     */
    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token
    ): bool {
        $user = $token->getUser();

        if (($user instanceof User) === false) {
            return false;
        }

        /** @var \App\Entity\Employer $employer l'employeur. */
        $employer = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($employer, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    /**
     * Détermine si l'utilisateur peut voir l'employeur.
     * @param \App\Entity\Employer $employer l'employeur.
     * @param \App\Entity\User $user l'utilisateur.
     * @return bool si l'utilisateur peut voir l'employeur.
     */
    private function canView(Employer $employer, User $user): bool
    {
        if ($employer->getId() !== $user->getEmployer()->getId()) {
            return false;
        }

        return true;
    }
}

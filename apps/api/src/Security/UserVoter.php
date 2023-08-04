<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Le voteur pour les interventions.
 */
class UserVoter extends Voter
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
            || ($subject instanceof User) === false
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
        $securityUser = $token->getUser();

        if (($securityUser instanceof User) === false) {
            return false;
        }

        /** @var \App\Entity\User $securityUser l'utilisateur connecté. */
        $user = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($user, $securityUser),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    /**
     * Détermine si l'utilisateur peut voir les données d'un utilisateur.
     * @param \App\Entity\USer $user l'utilisateur demandé.
     * @param \App\Entity\User $securityUser l'utilisateur connecté.
     * @return bool si l'utilisateur peut voir les données d'un utilisateur.
     */
    private function canView(User $user, User $securityUser): bool
    {
        if ($user->getEmployer()->getId() !== $securityUser->getEmployer()->getId()) {
            return false;
        }

        return true;
    }
}

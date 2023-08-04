<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Intervention;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Le voteur pour supprimer une intervention.
 */
class DeleteInterventionVoter extends Voter
{
    // Constantes :

    /**
     * La possibilité de supprimer les données.
     */
    const DELETE = 'DELETE';


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
            $attribute !== self::DELETE
            || ($subject instanceof Intervention) === false
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

        /** @var \App\Entity\Intervention $intervention l'intervention. */
        $intervention = $subject;

        if ($intervention->getEmployer()->getId() !== $user->getEmployer()->getId()) {
            return false;
        }

        return true;
    }
}

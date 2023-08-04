<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Intervention;

/**
 * Trait pour la génération d'un substitut
 * de l'entité Intervention.
 */
trait InterventionMock
{
    // Méthodes :

    /**
     * Renvoie un substitut de l'entité Intervention.
     * @return \App\Entity\Intervention un substitut de l'entité Intervention.
     */
    private function getMockForIntervention(): Intervention
    {
        return $this->getMockBuilder(Intervention::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}

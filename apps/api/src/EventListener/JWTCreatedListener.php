<?php

declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
   /**
    * @param \Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent $event
    */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();

        /**
         * @var \App\Entity\User l'utilisateur.
         */
        $user = $event->getUser();
        $payload['userId'] = $user->getId();
        $payload['employerId'] = $user->getEmployer()->getId();

        $event->setData($payload);
    }
}

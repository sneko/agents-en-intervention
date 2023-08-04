<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\S3GetUrlService;

final class InterventionProvider implements ProviderInterface
{
    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param \ApiPlatform\State\ProviderInterface $itemProvider
     * @param \App\Service\S3GetUrlService $s3GetUrlService
     */
    public function __construct(
        private ProviderInterface $itemProvider,
        private readonly S3GetUrlService $s3GetUrlService
    ) {
    }


    // Méthodes :

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /**
         * @var \App\Entity\Intervention $intervention l'intervention.
         */
        $intervention = $this->itemProvider->provide($operation, $uriVariables, $context);

        foreach ($intervention->getPictures() as $picture) {
            if ($this->s3GetUrlService->fileExist($picture->getFileName()) === true) {
                $pictureURL = $this->s3GetUrlService->getSignedURL($picture->getFileName());
                $picture->setURL($pictureURL);
            } else {
                $intervention->removePicture($picture);
            }
        }

        return $intervention;
    }
}

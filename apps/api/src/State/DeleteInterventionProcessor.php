<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\S3DeleteService;

/**
 * Le processor pour supprimer une intervention.
 */
class DeleteInterventionProcessor implements ProcessorInterface
{
    // Propriétés :

    private ProcessorInterface $removeProcessor;

    /**
     * @var \App\Service\S3DeleteService le service pour la suppression.
     */
    private S3DeleteService $s3DeleteService;


    // Méthodes magiques :

    /**
     * Le constructeur
     * @param \ApiPlatform\State\ProcessorInterface $removeProcessor
     * @param \App\Service\S3DeleteService $s3DeleteService le service pour la suppression.
     */
    public function __construct(
        ProcessorInterface $removeProcessor,
        S3DeleteService $s3DeleteService
    ) {
        $this->removeProcessor = $removeProcessor;
        $this->s3DeleteService = $s3DeleteService;
    }


    // Méthodes :

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /**
         * @var \App\Entity\Picture[] les photos.
         */
        $pictures = $data->getPictures();

        foreach ($pictures as $picture) {
            $this->s3DeleteService->deleteFile($picture->getFileName());
        }

        return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
   }
}

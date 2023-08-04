<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Un service pour supprimer un fichier dans un S3.
 */
final class S3DeleteService extends S3Service
{
    // Méthodes :

    /**
     * Supprime un fichier.
     * @param string $key la clé.
     */
    public function deleteFile(string $key): void
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key
        ]);
    }
}

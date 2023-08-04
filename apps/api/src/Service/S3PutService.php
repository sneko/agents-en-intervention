<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Un service pour mettre un fichier dans un S3.
 */
final class S3PutService extends S3Service
{
    // Méthodes :

    /**
     * Ajoute un fichier.
     * @param UploadedFile $file le fichier.
     * @param string $key la clé.
     */
    public function putFile(UploadedFile $file, string $key): void
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Body'   => $file->getContent(),
            'Key'    => $key
        ]);
    }
}

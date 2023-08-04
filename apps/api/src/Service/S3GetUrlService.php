<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Un service pour récupérer
 * l'URL d'un fichier dans un S3.
 */
final class S3GetUrlService extends S3Service
{
    // Méthodes :

    /**
     * Renvoie l'URL signée.
     * @param string $key la clé.
     * @return string l'URL signée.
     */
    public function getSignedURL(string $key): string
    {
        $getObjectCommand = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key'    => $key
        ]);

        $request = $this->s3Client->createPresignedRequest(
            $getObjectCommand,
            '+1 hour'
        );

        return (string) $request->getUri();
    }
}

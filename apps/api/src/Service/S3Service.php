<?php

declare(strict_types=1);

namespace App\Service;

use Aws\S3\S3Client;

/**
 * Une classe pour accéder au S3.
 */
abstract class S3Service
{
    // Propriétés :

    /**
     * @var \Aws\S3\S3Client le client S3.
     */
    protected S3Client $s3Client;

    /**
     * @var string le nom du seau.
     */
    protected string $bucket;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string $endPoint l'URL du point d'accès.
     * @param string $accessKey la clé d'accès.
     * @param string $secretKey la clé secrète.
     * @param string $region la région.
     * @param string $bucket le seau.
     */
    public function __construct(
        string $endPoint,
        string $accessKey,
        string $secretKey,
        string $region,
        string $bucket
    ) {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'endpoint' => $endPoint,
            'credentials' => [
                'key'    => $accessKey,
                'secret' => $secretKey,
            ],
            'region' => $region
        ]);
        $this->bucket = $bucket;
    }

    // Méthodes :

    /**
     * Renvoie true si le fichier exite dans le seau.
     * @param string $key la clé (le nom) du fichier.
     * @return bool si le fichier exite dans le seau.
     */
    public function fileExist(string $key): bool
    {
        return $this->s3Client->doesObjectExist($this->bucket, $key);
    }
}

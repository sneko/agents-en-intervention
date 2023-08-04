<?php

declare(strict_types=1);

namespace App\Encoder;

use App\Service\S3PutService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    // Constantes

    public const FORMAT = 'multipart';


    // Propriétés :

    private RequestStack $requestStack;

    private S3PutService $s3Service;


    // Méthodes magiques :

    public function __construct(
        RequestStack $requestStack,
        S3PutService $s3Service
    ) {
        $this->requestStack = $requestStack;
        $this->s3Service = $s3Service;
    }


    // Méthodes :

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }

    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        $newFileName = $this->uploadFile(
            $request->files->get('picture_file'),
            $request->get('intervention'),
        );

        $request->request->add(['fileName' => $newFileName,]);

        return array_map(static function (string $element) {
            // Multipart form values will be encoded in JSON.
            $decoded = json_decode($element, true);

            return \is_array($decoded) ? $decoded : $element;
        }, $request->request->all()) + $request->files->all();
    }

    private function uploadFile(
        UploadedFile $uploadedFile,
        string $interventionURI,
    ): string {
        $interventionURIItems = explode('/', $interventionURI);
        $interventionId = end($interventionURIItems);

        $newFileName = $this->generateNewFileName(
            $interventionId,
            $uploadedFile->getClientOriginalExtension(),
        );

        $this->s3Service->putFile(
            $uploadedFile,
            $newFileName
        );

        return $newFileName;
    }

    private function generateNewFileName(
        string $interventionId,
        string $fileExtension
    ): string {
        $newFileName = '';
        $fileIndex = 0;

        do {
            $fileIndex++;
            $newFileName = 'interventions/' . $interventionId . '-' . $fileIndex . '.' . $fileExtension;
        } while ($this->s3Service->fileExist($newFileName) === true);

        return $newFileName;
    }
}

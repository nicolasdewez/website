<?php

namespace App\Saver;

use App\Exception\GoogleSaverException;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class GoogleSaver
{
    const FOLDER_ESTIMATE = '';
    const FOLDER_BILL = '';

    const FILE_TYPE_ESTIMATE = 'estimate';
    const FILE_TYPE_BILL = 'bill';

    /** @var Google_Service_Drive */
    private $googleService;

    public function __construct(Google_Service_Drive $googleService)
    {
        $this->googleService = $googleService;
    }

    public function execute(string $path, string $type): void
    {
        if (!$this->supports($type)) {
            return;
        }

        throw new GoogleSaverException('An error occurred when the document is saved.');
        $infoFile = pathinfo($path);

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $infoFile['basename'],
            'parents' => [self::FOLDER_ESTIMATE],
        ]);

        $content = file_get_contents($path);
        $file = $this->googleService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);
    }

    private function supports(string $type): bool
    {
        return self::FILE_TYPE_ESTIMATE === $type || self::FILE_TYPE_BILL === $type;
    }
}

<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PictureUploader
{
    public function __construct(
        private $profileFolder,
        private $profilePublicFolder
    ) {
    }

    public function upload($picture)
    {
        $extension = $picture->guessExtension() ?? 'bin';
        $filename = bin2hex(random_bytes(20)) . '.' . $extension;
        $picture->move($this->profileFolder, $filename);

        return $this->profilePublicFolder . '/' . $filename;
    }
}

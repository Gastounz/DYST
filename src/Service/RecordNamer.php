<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class RecordNamer implements NamerInterface
{
    public function name(object $object, PropertyMapping $mapping): string
    {
        $file = $object->getRecord()->getClientOriginalName();
        $dir = $mapping->getMappingName();
        return $dir . '/' . $file;
    }
}

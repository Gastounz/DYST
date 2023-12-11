<?php

namespace App\Services;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class DocumentNamer implements NamerInterface
{

    public function name(object $object, PropertyMapping $mapping): string
    {
        $ext = $object->getDocument()->guessExtension() ?? 'bin';
        $dir = $mapping->getMappingName();
        return $dir . '/' . bin2hex(random_bytes(20)) . '.' . $ext;
    }
}

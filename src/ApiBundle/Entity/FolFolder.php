<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelTypeFolderType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FolFolder
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\FolFolderRepository")
 */
class FolFolder extends BfoBaseFolder
{

    /**
     * Get folType
     *
     * @return string
     */
    public function getFolType()
    {
        return EnumLabelTypeFolderType::FOLDER_TYPE;
    }
}

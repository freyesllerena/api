<?php

namespace ApiBundle\Form\DataTransformer;

use ApiBundle\Entity\FolFolder;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FolFolderToNumberTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $folder
     *
     * @return string
     */
    public function transform($folder)
    {
        if (null === $folder) {
            return '';
        }

        return $folder->getFolId();
    }

    /**
     * Transforme un Id en objet FolFolder
     *
     * @param mixed $folderNumber L'id d'un dossier
     *
     * @return FolFolder|null
     * @throws TransformationFailedException
     */
    public function reverseTransform($folderNumber)
    {
        if (!$folderNumber) {
            return;
        }

        $folder = $this->em->getRepository('ApiBundle:FolFolder')->find($folderNumber);

        if (null === $folder) {
            throw new TransformationFailedException();
        }

        return $folder;
    }
}
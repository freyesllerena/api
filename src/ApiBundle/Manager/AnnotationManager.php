<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\AdoAnnotationsDossier;
use ApiBundle\Entity\AnoAnnotations;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelEtatType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AnnotationManager
{
    const ANNOTATION_TYPE_DOCUMENT = 'document';

    const ANNOTATION_TYPE_FOLDER = 'folder';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UsrUsers
     */
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Récupère les annotations d'un document
     *
     * @param integer $documentId L'id du document
     * @param string $annotationType Le type d'annotation à récupérer
     *
     * @return array
     */
    public function retrieveAnnotationsList($documentId, $annotationType = self::ANNOTATION_TYPE_DOCUMENT)
    {
        switch ($annotationType) {
            case self::ANNOTATION_TYPE_DOCUMENT:
                $annotationList = $this->entityManager
                    ->getRepository('ApiBundle:AnoAnnotations')
                    ->findAnnotationsList(
                        $documentId,
                        $this->user->getUsrLogin()
                    );
                foreach ($annotationList as &$annotation) {
                    $annotation['anoLogin'] = [
                        'usrLogin' => $annotation['usrLogin'],
                        'usrNom' => $annotation['usrNom'],
                        'usrPrenom' => $annotation['usrPrenom']
                    ];
                    unset($annotation['usrLogin']);
                    unset($annotation['usrNom']);
                    unset($annotation['usrPrenom']);
                }
                return $annotationList;
            case self::ANNOTATION_TYPE_FOLDER:
                $annotationList = $this->entityManager
                    ->getRepository('ApiBundle:AdoAnnotationsDossier')
                    ->findAnnotationsFolderList(
                        $documentId,
                        $this->user->getUsrLogin()
                    );
                foreach ($annotationList as &$annotation) {
                    $annotation['adoLogin'] = [
                        'usrLogin' => $annotation['usrLogin'],
                        'usrNom' => $annotation['usrNom'],
                        'usrPrenom' => $annotation['usrPrenom']
                    ];
                    unset($annotation['usrLogin']);
                    unset($annotation['usrNom']);
                    unset($annotation['usrPrenom']);
                }
                return $annotationList;
        }
        return [];
    }

    /**
     * Lit une annotation
     *
     * @param integer $annotationId L'id de l'annotation
     * @param string $annotationType Le type d'annotation (pour un document ou un dossier)
     *
     * @return AdoAnnotationsDossier|AnoAnnotations|null
     */
    public function getAnnotationById($annotationId, $annotationType = self::ANNOTATION_TYPE_DOCUMENT)
    {
        switch ($annotationType) {
            case self::ANNOTATION_TYPE_DOCUMENT:
                return $this->entityManager->getRepository('ApiBundle:AnoAnnotations')->find($annotationId);
            case self::ANNOTATION_TYPE_FOLDER:
                return $this->entityManager->getRepository('ApiBundle:AdoAnnotationsDossier')->find($annotationId);
        }
        return null;
    }

    /**
     * Instancie une annotation
     *
     * @param string $annotationType Le type d'annotation (pour un document ou un dossier)
     *
     * @return AdoAnnotationsDossier|AnoAnnotations|null
     */
    public function instantiateAnnotation($annotationType = self::ANNOTATION_TYPE_DOCUMENT)
    {
        switch ($annotationType) {
            case self::ANNOTATION_TYPE_DOCUMENT:
                $annotation = new AnoAnnotations();
                $annotation->setAnoLogin($this->user);
                return $annotation;
            case self::ANNOTATION_TYPE_FOLDER:
                $annotation = new AdoAnnotationsDossier();
                $annotation->setAdoLogin($this->user);
                return $annotation;
        }
        return null;
    }

    /**
     * Vérifie que l'utilisateur est propriétaire de son annotation
     *
     * @param object $annotation Une instance de AnoAnnotations ou AdoAnnotationsDossier
     *
     * @return bool
     */
    public function isOwner($annotation)
    {
        if ($annotation instanceof AnoAnnotations) {
            return $annotation->getAnoLogin()->getUsrLogin() == $this->user->getUsrLogin();
        } elseif ($annotation instanceof AdoAnnotationsDossier) {
            return $annotation->getAdoLogin()->getUsrLogin() == $this->user->getUsrLogin();
        }
        return false;
    }

    /**
     * Vérifie que l'annotation est la dernière ajoutée
     *
     * @param object $annotation Une instance de AnoAnnotations ou AdoAnnotationsDossier
     *
     * @return bool|null
     */
    public function isLastestAnnotation($annotation)
    {
        if ($annotation instanceof AnoAnnotations) {
            if ($listAnnotations = $this->retrieveAnnotationsList($annotation->getAnoFiche()->getIfpId())) {
                return $listAnnotations[0]['anoId'] == $annotation->getAnoId();
            }
        } elseif ($annotation instanceof AdoAnnotationsDossier) {
            if ($listAnnotations = $this->retrieveAnnotationsList(
                $annotation->getAdoFolder()->getFolId(),
                self::ANNOTATION_TYPE_FOLDER
            )
            ) {
                return $listAnnotations[0]['adoId'] == $annotation->getAdoId();
            }
        }
        return null;
    }

    /**
     * Met à jour le statut de l'annotation
     *
     * @param object $annotation Une instance de AnoAnnotations ou AdoAnnotationsDossier
     */
    public function updateAnnotationEtat($annotation)
    {
        if ($annotation instanceof AnoAnnotations || $annotation instanceof AdoAnnotationsDossier) {
            if ($annotation instanceof AnoAnnotations) {
                $annotation->setAnoEtat(EnumLabelEtatType::DELETE_ETAT);
            } else {
                $annotation->setAdoEtat(EnumLabelEtatType::DELETE_ETAT);
            }
            $this->entityManager->persist($annotation);
            $this->entityManager->flush();
        }
    }

    /**
     * Crée une annotation
     *
     * @param object $annotation Une instance de AnoAnnotations ou AdoAnnotationsDossier
     *
     * @return null
     */
    public function createAnnotation($annotation)
    {
        if ($annotation instanceof AnoAnnotations || $annotation instanceof AdoAnnotationsDossier) {
            $this->entityManager->persist($annotation);
            $this->entityManager->flush();
            return $annotation;
        }
        return null;
    }
}

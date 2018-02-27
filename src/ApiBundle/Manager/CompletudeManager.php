<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\ComCompletude;
use ApiBundle\Entity\CtyCompletudeType;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IinIdxIndiv;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Repository\ComCompletudeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompletudeManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ComCompletudeRepository
     */
    private $completudeRepository;

    /**
     * @var UsrUsers
     */
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->completudeRepository = $this->entityManager->getRepository('ApiBundle:ComCompletude');
        $this->user = $this->container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Récupère la complétude
     *
     * @param integer $completudeId L'id d'une complétude
     *
     * @return ComCompletude
     */
    public function getCompletudeById($completudeId)
    {
        return $this->completudeRepository->find($completudeId);
    }

    /**
     * Vérifie que l'objet est une instance de ComCompletude, lit l'objet et contrôle le propriétaire
     *
     * @param null|ComCompletude $completude Une instance de ComCompletude
     *
     * @return array|string
     */
    public function validateCompletudeAndCheckIsOwner($completude)
    {
        // Validation de la complétude
        if (!$completude instanceof ComCompletude) {
            return [ComCompletude::ERR_DOES_NOT_EXIST, ''];
        } elseif (!$this->isOwner($completude)) {
            // Contrôle du propriétaire
            return [ComCompletude::ERR_NOT_OWNER, $completude->getComLibelle()];
        }
        return '';
    }

    /**
     * Vérifie que l'utilisateur est propriétaire de la complétude
     *
     * @param ComCompletude $completude
     *
     * @return bool
     */
    public function isOwner(ComCompletude $completude)
    {
        return $completude->getComUser()->getUsrLogin() == $this->user->getUsrLogin();
    }

    /**
     * Instancie une nouvelle complétude
     *
     * @return ComCompletude
     */
    public function instantiateCompletude()
    {
        $completude = new ComCompletude();
        $completude->setComUser($this->user);
        return $completude;
    }

    /**
     * Vérifie que la complétude n'existe pas déjà pour l'utilisateur
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     *
     * @return mixed
     */
    public function hasAnotherCompletudeLabel(ComCompletude $completude)
    {
        return $this->completudeRepository
            ->findAnotherCompletudeLabel($completude, $this->user
                ->getUsrLogin());
    }

    /**
     * Met à jour une complétude
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     * @param array $drawersList Une liste de tiroirs
     *
     * @return ComCompletude
     */
    public function updateCompletude(ComCompletude $completude, array $drawersList)
    {
        $drawersInserted = $this->retrieveCompletudeDrawersList($completude);
        $drawersToInsert = array_diff($drawersList, $drawersInserted);
        $drawersToDelete = array_diff($drawersInserted, $drawersList);
        $this->removeDrawers($drawersToDelete, $completude);
        return $this->createCompletude($completude, $drawersToInsert);
    }

    /**
     * Crée une complétude
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     * @param array $drawersList Une liste de tiroirs
     *
     * @return ComCompletude
     */
    public function createCompletude(ComCompletude $completude, array $drawersList)
    {
        foreach ($drawersList as $drawer) {
            $this->addDrawer($drawer, $completude);
        }
        $this->entityManager->persist($completude);
        $this->entityManager->flush();
        return $completude;
    }

    /**
     * Ajoute des tiroirs à une complétude
     *
     * @param string $drawer Un tiroir
     * @param ComCompletude $completude Une instance de ComCompletude
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function addDrawer($drawer, ComCompletude $completude)
    {
        $completudeType = new CtyCompletudeType();
        $completudeType->setCtyCompletude($completude);
        $completudeType->setCtyType($this->entityManager->getReference('ApiBundle:TypType', $drawer));
        $this->entityManager->persist($completudeType);
    }

    /**
     * Supprime des tiroirs d'une complétude
     *
     * @param array $drawersList Une liste de tiroirs
     * @param ComCompletude $completude Une instance de ComCompletude
     */
    public function removeDrawers(array $drawersList, ComCompletude $completude)
    {
        if (count($drawersList)) {
            $this->entityManager->getRepository('ApiBundle:CtyCompletudeType')
                ->deleteDrawers($drawersList, $completude->getComIdCompletude());
        }
    }

    /**
     * Supprime une complétude
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     */
    public function removeCompletude(ComCompletude $completude)
    {
        $this->entityManager->remove($completude);
        $this->entityManager->flush();
    }

    /**
     * Récupère la liste des complétude de l'utilisateur
     *
     * @return array
     */
    public function retrieveCompletudeList()
    {
        $completudeResult = $this->completudeRepository
            ->findCompletudeList($this->user->getUsrLogin());

        $completudeList = [];
        $drawersAllowed = $this->container->get('api.manager.user')->getApplicationFilterList();
        foreach ($completudeResult as $completude) {
            $drawer = $completude['ctyType'];
            if (!isset($completudeList[$completude['comIdCompletude']])) {
                $completude['comUser'] = [
                    'usrLogin' => $completude['usrLogin'],
                    'usrNom' => $completude['usrNom'],
                    'usrPrenom' => $completude['usrPrenom']
                ];
                unset($completude['usrLogin']);
                unset($completude['usrNom']);
                unset($completude['usrPrenom']);
                $completude['comEmail'] = explode(';', $completude['comEmail']);
                $completude['ctyType'] = [];
                $completudeList[$completude['comIdCompletude']] = $completude;
            }
            if (in_array($drawer, $drawersAllowed)) {
                $completudeList[$completude['comIdCompletude']]['ctyType'][] = $drawer;
            }

        }
        return array_values(
            $completudeList
        );
    }

    /**
     * Renvoie la liste des tiroirs d'une complétude
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     *
     * @return mixed
     */
    public function retrieveCompletudeDrawersList(ComCompletude $completude)
    {
        return array_column(
            $this->entityManager->getRepository('ApiBundle:CtyCompletudeType')
                ->findDrawers($completude->getComIdCompletude()),
            'typCode'
        );
    }

    /**
     * Récupère la liste des records d'une complétude sans documents d'un utilisateur
     *
     * @param object $params La liste des paramètres passés au WS
     *
     * @return array
     */
    public function executeCompletude($params)
    {
        $data = [];
        if ($params->node->withoutDoc) {
            $resultQuery = $this->completudeRepository->findCompletudeWithoutDoc(
                $params,
                $this->getFieldsCompletudeWithoutDoc()
            );
            if ($resultQuery) {
                $data['totalCount'] = $this->completudeRepository->countCompletudeWithoutDoc($params);
                foreach ($resultQuery as $valueListRows) {
                    $data['items'][] = $valueListRows;
                }
            }
        } else {
            $data = $this->container->get('api.manager.document')
                ->retrieveListDocumentWS($params);
        }
        return $data;
    }

    /**
     * Récupère la liste des champs à utiliser pour la complétude sans documents
     *
     * @return array
     */
    private function getFieldsCompletudeWithoutDoc()
    {
        $fields = [];
        $metadatas = $this->container->getParameter('metadata_documents');
        foreach ($metadatas as $field => $properties) {
            if (!empty($properties['completudeWithoutDoc'])) {
                $fields[] = IinIdxIndiv::IIN_PREFIX . $field;
            }
        }
        return $fields;
    }
}

<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\TypType;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Repository\TypTypeRepository;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\AbstractQuery;
use Lsw\MemcacheBundle\Cache\MemcacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TypeManager
 * @package ApiBundle\Manager
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class TypeManager
{
    /**
     * @var TypTypeRepository
     */
    private $typeRepository;

    /**
     * @var DictionaryManager
     */
    private $dictionaryManager;

    /**
     * @var MemcacheInterface
     */
    private $memcache;

    /**
     * Le n° d'instance
     *
     * @var string
     */
    private $numInstance;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UsrUsers
     */
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->typeRepository = $container->get('doctrine')->getRepository('ApiBundle:TypType');
        $this->dictionaryManager = $container->get('api.manager.dictionnaire');
        $this->memcache = $container->get('memcache.default');
        $userToken = $container->get('security.token_storage')->getToken();
        /** @var UserToken $userToken */
        $this->numInstance = $userToken->getNumInstance();
        $this->container = $container;
        $this->user = $userToken->getUser();
    }

    /**
     * Retourne le plan de classement si caché sinon le construit et le retourne
     *
     * @param string $device Le support pour lequel on récupère les traductions
     *
     * @return array|mixed
     */
    public function getTree($device = DicDictionnaire::DEFAULT_DIC_DEVICE)
    {
        $language = $this->user->getUsrLangue() ?
            $this->user->getUsrLangue() : $this->container->getParameter('locale');
        if ($planCached =
            $this->memcache->get('API::' . $this->numInstance . '::classificationPlan_' . $language . '_' . $device)
        ) {
            // Décodage
            $classificationPlan = json_decode($planCached);
        } else {
            // Construction du plan de classement
            $classificationPlan = $this->buildTree($device, $language);
        }

        return $classificationPlan;
    }

    /**
     * Retourne le domaine
     *
     * @param int $typeIdNiveau Le niveau
     *
     * @return mixed
     */
    public function getTypIdLevel1($typeIdNiveau)
    {
        return substr($typeIdNiveau, 0, 1);
    }

    /**
     * Retourne le sous-domaine
     *
     * @param int $typeIdNiveau Le niveau
     *
     * @return mixed
     */
    public function getTypIdLevel2($typeIdNiveau)
    {
        return substr($typeIdNiveau, 1, 2);
    }

    /**
     * Retourne le sous sous-domaine
     *
     * @param int $typeIdNiveau Le niveau
     *
     * @return mixed
     */
    public function getTypIdLevel3($typeIdNiveau)
    {
        return substr($typeIdNiveau, 3, 2);
    }

    /**
     * Recherche récursivement les tiroirs dans le plan de classement à partir du noeud fourni
     *
     * @param int $idLevel L'id du noeud recherché
     *
     * @return array
     */
    public function retrieveLevelDrawersList($idLevel)
    {
        // Initialisation
        $drawers = [];
        // Contrôle du noeud
        if (is_numeric($idLevel)) {
            // Lecture dans memcache
            if ($drawersCached =
                $this->memcache->get('API::' . $this->numInstance . '::listeTiroirsParNiveau_' . $idLevel)
            ) {
                // Décodage
                $drawers = json_decode($drawersCached);
            } else {
                // Construction du plan de classement
                $classificationPlan = $this->getTree();
                // Recherche du noeud correspondant à la recherche
                $levelClassification = $this->browseAndFindLevel($idLevel, $classificationPlan);
                // Récupération des tiroirs contenus dans le noeud sélectionné
                $drawers = $this->extractLevelDrawers($levelClassification);
                // Stockage des tiroirs du noeud dans memcache pendant 24 heures
                $this->memcache->set(
                    'API::' . $this->numInstance . '::listeTiroirsParNiveau_' . $idLevel,
                    json_encode($drawers),
                    0,
                    86400
                );
            }
        }

        return $drawers;
    }

    /**
     * Retourne la liste des tiroirs classés en fonction de leur type
     *
     * @param string $type Soit tiroirs individuels, collectifs ou les deux
     *
     * @return array|mixed
     */
    public function getListDrawersType($type = TypType::TYP_MIXED)
    {
        if (!in_array($type, [TypType::TYP_INDIVIDUAL, TypType::TYP_COLLECTIVE, TypType::TYP_MIXED])) {
            return [];
        } else {
            // Vérifie si la liste des tiroirs n'est pas cachée
            if ($drawersTypeCached = $this->memcache->get('API::' . $this->numInstance . '::listeTiroirsParType')) {
                // Décodage
                $drawersType = json_decode($drawersTypeCached, true);
            } else {
                // Construction de la liste des tiroirs en fonction de leur type
                $drawersType = $this->buildListDrawersType(
                    $this->typeRepository
                        ->findAll(AbstractQuery::HYDRATE_ARRAY)
                );
            }
            // Retourne tous les tiroirs si les deux, sinon soit individuel, soit collectif
            return $type == TypType::TYP_MIXED ?
                array_merge(
                    $drawersType[TypType::TYP_INDIVIDUAL],
                    $drawersType[TypType::TYP_COLLECTIVE]
                ) : $drawersType[$type];
        }
    }

    /**
     * Construit la liste des tiroirs en fonction de leur type
     *
     * @param array $typItems Une liste de tiroirs
     *
     * @return array
     */
    public function buildListDrawersType(array $typItems)
    {
        // Initialisation
        $drawersType = [];
        foreach ($typItems as $typItem) {
            // Récupère le tiroir et le classe en fonction de son type
            $drawersType[$typItem['typIndividuel'] ?
                TypType::TYP_INDIVIDUAL : TypType::TYP_COLLECTIVE][] = $typItem['typCode'];
        }
        // Cache pendant 24 heures
        $this->memcache->set(
            'API::' . $this->numInstance . '::listeTiroirsParType',
            json_encode($drawersType),
            0,
            86400
        );
        // Retourne les tiroirs classés en fonction de leur type individuel ou collectif
        return $drawersType;
    }

    /**
     * Retourne l'arborescence du plan de classement sous forme d'un tableau d'objets
     *
     * @param string $device Le support pour lequel récupérer les traductions
     * @param string $language La langue voulue
     *
     * @return array    Une arborescence ou une erreur
     */
    private function buildTree($device, $language)
    {
        // Récupération des records
        $typItems = $this->typeRepository->findAll(AbstractQuery::HYDRATE_ARRAY);
        // Comptage
        if (!count($typItems)) {
            return [];
        }
        // Récupération des traductions
        $this->dictionaryManager->getDictionnaire($device);
        // On crée le noeud root
        $typLevelAssociative['ROOT'] = $this->createTypRoot();

        // On boucle sur chaque record
        foreach ($typItems as $typItem) {
            // Récupération du 1er niveau
            $typIdLevel1 = $this->getTypIdLevel1($typItem['typIdNiveau']);
            // Récupération du 2ème niveau
            $typIdLevel2 = $this->getTypIdLevel2($typItem['typIdNiveau']);
            // Récupération du 3ème niveau
            $typIdLevel3 = $this->getTypIdLevel3($typItem['typIdNiveau']);

            // Création et affectation du 1er niveau
            $typLevelAssociative = $this->buildTypLevel('ROOT', $typIdLevel1, 1, $typLevelAssociative);

            if ($typIdLevel2 == '') {
                // Affectation du tiroir au 1er niveau
                $typLevelAssociative = $this->buildTypCode($typIdLevel1, $typItem, $typLevelAssociative);
                // Inutile de tester le niveau 3 qui est vide
                continue;
            } else {
                // Création et affectation du 2ème niveau
                $typLevelAssociative = $this->buildTypLevel($typIdLevel1, $typIdLevel2, 2, $typLevelAssociative);
            }

            if ($typIdLevel3 == '') {
                // Affectation du tiroir au 2ème niveau
                $typLevelAssociative = $this->buildTypCode($typIdLevel2, $typItem, $typLevelAssociative);
            } else {
                // Création et affectation du 3ème niveau
                $typLevelAssociative = $this->buildTypLevel($typIdLevel2, $typIdLevel3, 3, $typLevelAssociative);
                // Affectation du tiroir au 3ème niveau
                $typLevelAssociative = $this->buildTypCode($typIdLevel3, $typItem, $typLevelAssociative);
            }
        }
        // Stockage de la liste des tiroirs individuels ou collectifs dans le cache
        $this->buildListDrawersType($typItems);
        // Création de l'arborescence récursivement
        $classificationPlan = $this
            ->buildChildrenProperties($typLevelAssociative['ROOT'], $typLevelAssociative)->children;
        // Cache pendant 24 heures
        $this->memcache->set(
            'API::' . $this->numInstance . '::classificationPlan_' . $language . '_' . $device,
            json_encode($classificationPlan),
            0,
            86400
        );

        return $classificationPlan;
    }

    /**
     * Construit le niveau d'un plan de classement (création de l'objet)
     *
     * @param string|integer $parent Le noeud parent
     * @param integer $child Le noeud enfant
     * @param integer $level Le niveau
     * @param array $arrayAssociative Un tableau dans lequel on crée le noeud enfant
     * et on affecte l'enfant au noeud parent
     *
     * @return array
     */
    private function buildTypLevel($parent, &$child, $level, array $arrayAssociative)
    {
        // Intégration des niveaux précédents pour que $child soit unique
        $child = $parent == 'ROOT' ? $child : $parent . $child;
        // Si pas d'existance de l'enfant, on crée l'objet et on l'affecte
        if (!isset($arrayAssociative[$child])) {
            // Traduction du niveau
            $levelTranslation = $this->getLevelTranslation($level, $child);
            // Création et affectation du niveau
            $arrayAssociative = $this->affectTypLevel($parent, $child, $levelTranslation, $arrayAssociative);
        }
        return $arrayAssociative;
    }

    /**
     * Construit le tiroir d'un plan de classement (création de l'objet)
     *
     * @param integer $parent Le noeud parent
     * @param array $child Les attributs du tiroir
     * @param array $arrayAssociative Un tableau dans lequel on crée le noeud enfant
     * et on affecte l'enfant au noeud parent
     *
     * @return array
     */
    private function buildTypCode($parent, array $child, array $arrayAssociative)
    {
        // Traduction du tiroir
        $drawerTranslation = $this->getDrawerTranslation($child['typCode']);
        // Création et affectation du tiroir
        return $this->affectTypCode($parent, $child, $drawerTranslation, $arrayAssociative);
    }

    /**
     * Récupère la traduction d'un tiroir
     *
     * @param string $drawer Le code du tiroir
     *
     * @return mixed|string
     */
    private function getDrawerTranslation($drawer)
    {
        $code = 'pdcTiroir-' . $drawer;
        return $this->getTranslation($code);
    }

    /**
     * Récupère la traduction d'un niveau
     *
     * @param integer $level Le niveau
     * @param integer $child Le noeud enfant
     *
     * @return mixed|string
     */
    private function getLevelTranslation($level, $child)
    {
        $code = 'pdcNiv' . $level . '-' . $child;
        return $this->getTranslation($code);
    }

    /**
     * Récupère la traduction de l'item
     *
     * @param string $code Le code pour lequel récupérer la traduction
     *
     * @return mixed|string
     */
    private function getTranslation($code)
    {
        return $this->dictionaryManager->getParameter($code) == '' ?
            $this->dictionaryManager->getParameter(TypType::ERR_NOT_TRANSLATED_MESSAGE) :
            $this->dictionaryManager->getParameter($code);
    }

    /**
     * Affecte un enfant à un élément parent en prenant soin de le créer
     *
     * @param integer $parent Le noeud parent
     * @param integer $child Le noeud enfant
     * @param string $translation La traduction du noeud enfant
     * @param array $arrayAssociative Un tableau dans lequel on crée le noeud enfant
     * et on affecte l'enfant au noeud parent
     *
     * @return array
     */
    private function affectTypLevel($parent, $child, $translation, array $arrayAssociative)
    {
        // Création du noeud
        $arrayAssociative[$child] = $this->createTypLevel($child, $translation);
        // On vérifie que le parent existe et est un objet dans lequel on peut affecter l'enfant
        if ($arrayAssociative[$parent] instanceof \stdClass && isset($arrayAssociative[$parent]->children)) {
            // Affectation de l'enfant au parent
            $arrayAssociative[$parent]->children[] = $child;
        }
        return $arrayAssociative;
    }

    /**
     * Affecte un tiroir à son parent
     *
     * @param integer $parent Le noeud parent
     * @param array $child Les attributs du tiroir
     * @param string $translation La traduction du tiroir
     * @param array $arrayAssociative Un tableau dans lequel on crée le noeud enfant
     * et on affecte l'enfant au noeud parent
     *
     * @return array
     */
    private function affectTypCode($parent, array $child, $translation, array $arrayAssociative)
    {
        // Création du noeud
        $arrayAssociative[$child['typCode']] = $this->createTypCode(
            $child['typCode'],
            $translation,
            $child['typIndividuel'],
            $child['typType']
        );
        // On vérifie que le parent existe et est un objet dans lequel on peut affecter l'enfant
        if ($arrayAssociative[$parent] instanceof \stdClass && isset($arrayAssociative[$parent]->children)) {
            // Affectation de l'enfant au parent
            $arrayAssociative[$parent]->children[] = $child['typCode'];
        }
        return $arrayAssociative;
    }

    /**
     * Retourne les propiétés settées d'un noeud parent à partir d'un tableau associatif
     *
     * @param \stdClass $properties Une instance de stdClass
     * @param array $arrayAssociative Un tableau associatif
     *
     * @return \stdClass
     */
    private function buildChildrenProperties(\stdClass $properties, array $arrayAssociative)
    {
        if (isset($properties->children)) {
            // Set le type de noeud (individuel, collectif ou mixte) en fonction de ses enfants
            $category = '';
            // On boucle sur chaque enfant du noeud parent
            foreach ($properties->children as &$child) {
                // Récupération du noeud enfant
                $child = $arrayAssociative[$child];
                // Ré-intégration du domaine, sous-domaine et sous sous-domaine
                $child->path = $properties->path;
                if ($properties->text) {
                    $child->path[] = $properties->text;
                }
                // Récupération récursive des enfants si le noeud en possède
                if (isset($child->children)) {
                    $child = $this->buildChildrenProperties($child, $arrayAssociative);
                }

                // On set le noeud parent à partir du type du 1er noeud enfant.
                // Par la suite, si le type du ou des noeud(s) suivants sont différents, le noeud parent est mixte
                $category = $category != '' && $category != $child->category ?
                    TypType::TYP_MIXED : $category = $child->category;
            }

            // Affectation du type du noeud
            $properties->category = $category;
        }

        return $properties;
    }

    /**
     * Parcours le plan de classement et retourne les enfants correspondants au noeud recherché
     *
     * @param int $idLevel L'id du noeud recherché
     * @param array $classificationPlan Le plan de classement
     *
     * @return array
     */
    private function browseAndFindLevel($idLevel, array $classificationPlan)
    {
        $levelClassification = [];
        // Parcours s'il y a des enfants
        if (count($classificationPlan)) {
            foreach ($classificationPlan as $properties) {
                // Si le noeud est trouvé, on annule le parcourt
                if (count($levelClassification)) {
                    break;
                } elseif ($properties->id == $idLevel) {
                    // On retourne les enfants du noeud
                    $levelClassification = isset($properties->children) ? $properties->children : [];
                    break;
                } else {
                    if (isset($properties->children)) {
                        // Parcours récursif des noeuds
                        $levelClassification = $this->browseAndFindLevel($idLevel, $properties->children);
                    }
                }
            }
        }

        return $levelClassification;
    }

    /**
     * Retourne les tirois à partir de la partie correspondant au noeud recherché
     *
     * @param array $levelClassification La partie du plan de classement correspondant au noeud recherché
     *
     * @return array
     */
    private function extractLevelDrawers(array $levelClassification)
    {
        // Initialisation d'un tableau de tiroirs vide
        $drawers = [];
        // Parcours si le noeud a des enfants
        if (count($levelClassification)) {
            // Parcours de la partie extraite du plan de classement
            foreach ($levelClassification as $properties) {
                // Est-ce une feuille ?
                if ($properties->leaf) {
                    // Stockage du tiroir
                    $drawers[] = $properties->id;
                } else {
                    if (isset($properties->children)) {
                        // Parcours récursif des noeuds afin de récupérer les tiroirs
                        $drawers = array_merge($drawers, $this->extractLevelDrawers($properties->children));
                    }
                }
            }
        }

        return $drawers;
    }

    /**
     * Retourne un objet TypRoot
     *
     * @return object
     */
    private function createTypRoot()
    {
        return (object)[
            'path' => [],
            'text' => [],
            'children' => []
        ];
    }

    /**
     * Retourne un objet TypLevel
     *
     * @param int $typLevel Le code du niveau
     * @param string $translation Une traduction du libellé du niveau
     *
     * @return object
     */
    private function createTypLevel($typLevel, $translation)
    {
        return (object)[
            'id' => $typLevel,
            'text' => $translation,
            'expanded' => false,
            'leaf' => false,
            'path' => [],
            'children' => []
        ];
    }

    /**
     * Retourne un objet TypCode
     *
     * @param int $typCode Le code du tiroir
     * @param string $translation Une traduction du libellé du tiroir
     * @param bool $individual Le type de tiroir (individuel ou collectif)
     * @param int $subscription Le niveau d'abonnement du tiroir (0, 1, 2...) qui détermine s'il est ou non visible
     *
     * @return object
     */
    private function createTypCode($typCode, $translation, $individual, $subscription)
    {
        // Set le type du tiroir (I: individuel, C: collectif)
        $category = $individual ? TypType::TYP_INDIVIDUAL : TypType::TYP_COLLECTIVE;
        return (object)[
            'id' => $typCode,
            'text' => $translation,
            'leaf' => true,
            'category' => $category,
            'subscription' => $subscription,
            'path' => []
        ];
    }
}

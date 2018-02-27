<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Listener\DynamicDatabaseConnectionListener;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MigrateTranslationsDatabaseCommand
 * @package ApiBundle\Command
 * @codeCoverageIgnore
 */
class MigrateTranslationsDatabaseCommand extends ContainerAwareCommand
{
    private $toMigrate = [
        /*
         * Libellé du code tiroir dans le plan de classement
         */
        'typedoc\_%\_libelle' => [
            'regex' => '/typedoc_(.+)_libelle/',
            'code' => 'pdcTiroir-#1'
        ],
        /*
         * Libellé du domaine - sous-domaine - sous sous-domaine dans un plan de classement
         */
        'domaine\_niv_\_%' => [
            'regex' => '/domaine_niv(\d{1})_(\d+)/',
            'code' => 'pdcNiv#1-#2'
        ],
        /*
         * Libellé des groupes - processus
         */
        'groupe\_processus\_%' => [
            'regex' => '/groupe_processus_(\d+)/',
            'code' => 'processusGroupe#1'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:migrate_translations_database_command')
            ->setDescription('Construction des traductions à partir de l\'ancienne base de données')
            ->addOption('instance', null, InputOption::VALUE_REQUIRED, 'Numéro d\'instance');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Traitement - Début');
        $container = $this->getContainer();

        // Récupération du service Doctrine et connexion à la BDD
        $doctrine = $container->get('doctrine');
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $doctrine->getConnection();
        $dynamicConnection = new DynamicDatabaseConnectionListener($container, $connection);
        $dynamicConnection->ikpCaller($input->getOption('instance'));
        // Entity Manager
        $entityManager = $doctrine->getManager();

        // Critères de recherche dans la BDD
        $translationCond = array_keys($this->toMigrate);
        // Récupération des regex -> code
        $translationRegExCode = array_column(
            $this->toMigrate,
            'code',
            'regex'
        );

        $output->writeln("Critères recherchés :\n" . implode("\n", $translationCond));
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $translations = $this->getRecordsMatchingConditionIsNull($translationCond, $entityManager);
        /** @var array $translations */
        $nbTranslations = count($translations);
        $output->writeln('Lignes à traiter (la0_langue0 et la1_langue1) : ' . $nbTranslations);

        if ($nbTranslations) {
            // Ré-organisation et construction des traductions
            $toPersist = $this->buildTranslations($translations, $translationRegExCode);
            // Parcours et création des records à partir du code setté
            $this->browseObjectToTranslateAndRemoveOldRecords($toPersist, $entityManager);
        }
        $output->writeln('Traitement - Fin');
    }

    /**
     * Retourne les codes qui ne sont pas settés
     *
     * @param array $conditions Les critères de recherche
     * @param EntityManager $entityManager Une instance de l'EntityManager
     *
     * @return QueryBuilder
     */
    private function getRecordsMatchingConditionIsNull(array $conditions, EntityManager $entityManager)
    {
        // Get DicDictionnaireRepository
        $repository = $entityManager->getRepository('ApiBundle:DicDictionnaire');
        // Création query
        $queryBuilder = $repository->createQueryBuilder('dic');
        // Ajout des codes nuls
        $queryBuilder->where($queryBuilder->expr()->isNull('dic.dicCode'));

        // Création or
        $orBuilder = $queryBuilder->expr()->orX();
        // Ajout des critères de recherche
        foreach ($conditions as $condition) {
            $orBuilder->add($queryBuilder->expr()->like('dic.dicOldVariable', '\'' . $condition . '\''));
        }
        // Ajout des conditions et tri sur oldVariable et oldProvenance de façon à avoir vdm_langue0 avant vdm_langue1
        $queryBuilder->andWhere($orBuilder)
            ->orderBy('dic.dicOldVariable', 'ASC')
            ->addOrderBy('dic.dicOldProvenance', 'ASC');

        return $repository->getTranslations($queryBuilder, 'fr_FR');
    }

    /**
     * Traite des items : création du code pour un record
     *
     * @param array $toPersist La liste des items à créer et leur record à supprimer
     * @param EntityManager $entityManager Une instance de l'EntityManager
     */
    private function browseObjectToTranslateAndRemoveOldRecords(array $toPersist, EntityManager $entityManager)
    {
        if (count($toPersist)) {
            $idsToRemove = [];

            foreach ($toPersist as $code => $languages) {
                // Boucle sur chaque device
                foreach (DicDictionnaire::$availableDevices as $device) {
                    // Nouvel objet DicDictionnaire
                    $dictionary = new DicDictionnaire();
                    // Set code
                    $dictionary->setDicCode($code);
                    // Set device
                    $dictionary->setDicSupport($device);

                    // Parcours des langues
                    foreach ($languages as $lang => $translation) {
                        // Langue
                        $dictionary->setTranslatableLocale($lang);
                        // Traduction
                        $dictionary->setDicValeur($translation['oldValeur']);
                        // Persist
                        $entityManager->persist($dictionary);
                        // Commit
                        $entityManager->flush();
                        // Suppression
                        $idsToRemove[] = $translation['id'];
                    }
                }
                $entityManager->clear();
            }
            // Purge de la table
            $queryBuilder = $entityManager->getRepository('ApiBundle:DicDictionnaire')
                ->createQueryBuilder('dic');
            $queryBuilder->delete()
                ->where($queryBuilder->expr()->in('dic.dicId', implode(',', $idsToRemove)))
                ->getQuery()
                ->execute();
        }
    }

    /**
     * Retourne un tableau d'éléments à créer dans la database en settant un code pour chaque record
     *
     * @param array $dictionaries La liste des traductions retournées
     * @param array $translationRegExCode La liste des regex avec leur code à générer
     *
     * @return array
     */
    private function buildTranslations(array $dictionaries, array $translationRegExCode)
    {
        $toPersist = [];
        foreach ($dictionaries as $dictionary) {
            foreach ($translationRegExCode as $regex => $code) {
                $matches = null;
                if (preg_match($regex, $dictionary->getDicOldVariable(), $matches)) {
                    // Ça match, on continue
                    if (count($matches)) {
                        unset($matches[0]);
                        // On crée un tableau array('#1', '#2', '#3' {...}) en fonction du nombre de matches
                        $search = array_map(
                            function ($k) {
                                return '#' . $k;
                            },
                            array_keys($matches)
                        );
                        // On remplace les #1, #2 ... par leur valeur dans $matches
                        $code = str_replace($search, $matches, $code);
                    }

                    // Récupération de la langue
                    $language = 'vdm_langue1' == $dictionary->getDicOldProvenance() ?
                        'en_EN' : 'fr_FR';
                    $toPersist[$code][$language] = [
                        'oldValeur' => $dictionary->getDicOldValeur(),
                        'id' => $dictionary->getDicId()
                    ];
                }
            }
        }

        return $toPersist;
    }
}

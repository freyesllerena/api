<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\IhaImportHabilitation;
use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\ProProfil;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelEtatReportType;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use ApiBundle\Form\IhaImportHabilitationType;
use ApiBundle\Form\UsrUsersType;
use ApiBundle\Repository\PdaProfilDefAppliRepository;
use ApiBundle\Repository\PdhProfilDefHabiRepository;
use ApiBundle\Repository\ProProfilRepository;
use ApiBundle\Repository\UsrUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class HabilitationManager
 * @package ApiBundle\Manager
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class HabilitationManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProProfilRepository
     */
    private $profilRepository;

    /**
     * @var PdaProfilDefAppliRepository
     */
    private $profilApplicatifRepository;

    /**
     * @var PdhProfilDefHabiRepository
     */
    private $profilPopulationRepository;

    /**
     * @var UsrUsersRepository
     */
    private $userRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var DictionaryManager
     */
    private $dictionaryManager;

    /**
     * @var SecurityManager
     */
    private $securityManager;

    /**
     * @var ExportExcelManager
     */
    private $exportExcelManager;

    /**
     * @var int
     */
    private $nbColsImportHabiFile;

    /**
     * @var array
     */
    private $colsImportHabiFile;

    /**
     * @var array
     */
    private $headersLabelFile;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->profilRepository = $this->entityManager->getRepository('ApiBundle:ProProfil');
        $this->profilApplicatifRepository = $this->entityManager->getRepository('ApiBundle:PdaProfilDefAppli');
        $this->profilPopulationRepository = $this->entityManager->getRepository('ApiBundle:PdhProfilDefHabi');
        $this->userRepository = $this->entityManager->getRepository('ApiBundle:UsrUsers');
        $this->securityManager = $container->get('api.manager.security');
        $this->userManager = $container->get('api.manager.user');
        $this->exportExcelManager = $container->get('api.manager.export_excel');
        $this->dictionaryManager = $container->get('api.manager.dictionnaire');
        $this->colsImportHabiFile = array_flip($container->getParameter('file_import')['structure']);
        $this->nbColsImportHabiFile = count($this->colsImportHabiFile);
        $this->headersLabelFile = $this->getHeadersLabelFile();
    }

    /**
     * Renvoie vrai si le Type MIME est correct
     *
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function hasCorrectMimeType(UploadedFile $file)
    {
        return UsrUsers::FILE_TYPE_MIME_IMPORT == $file->getMimeType();
    }

    /**
     * Renvoie vrai si l'extension est correcte
     *
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function hasCorrectExtension(UploadedFile $file)
    {
        return UsrUsers::FILE_EXTENSION_IMPORT == $file->getClientOriginalExtension();
    }

    /**
     * Importe les habilitations pour chaque utilisateur
     *
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function handleUsersHabilitationsFile(UploadedFile $file)
    {
        // Lecture du fichier
        $habilitationList = $this->readUsersHabilitationsFile($file);
        if (count($habilitationList)) {
            $report = [
                'ihaErreur' => 0,
                'ihaSucces' => 0,
                'ihaTraite' => 0
            ];
            $report['ihaRapport']['rapFichier'][] = array_merge(
                $this->headersLabelFile,
                [$this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_STATUS)],
                [$this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_ERROR)]
            );
            foreach ($habilitationList as &$lines) {
                // Intégration des habilitations
                $lines = $this->processUsersHabilitations($lines);
                // Mise en forme des lignes pour le login
                $report = $this->generateHabilitationReportLinesLogin($lines, $report);
            }
            // Initialisation des propriétés du rapport
            $report = $this->initializeReportProperties($report);
            // Intégration du rapport dans la base
            return $this->persistHabilitationReport($report);
        }
        return false;
    }

    /**
     * Lit un fichier d'import en masse des habilitations
     *
     * @param UploadedFile $file
     *
     * @return array
     */
    private function readUsersHabilitationsFile(UploadedFile $file)
    {
        $habiList = [];
        $openedFile = $file->openFile();
        while (!$openedFile->eof()) {
            if (($datas = $openedFile->fgetcsv(';')) && null !== $datas[0]) {
                // Vérification que la 1ère ligne n'est pas une ligne d'en-têtes
                if ('login' == strtolower($datas[$this->colsImportHabiFile['usrLogin']])) {
                    continue;
                }
                // Conversion des caractères encodés sous Windows
                array_walk($datas, function (&$value) {
                    $value = iconv('ISO-8859-15', 'UTF-8', $value);
                });
                if (!isset($habiList[$datas[$this->colsImportHabiFile['usrLogin']]])) {
                    $habiList[$datas[$this->colsImportHabiFile['usrLogin']]] = [];
                }
                // Ajout de la ligne dans un tableau indicé par le login
                $habiList[$datas[$this->colsImportHabiFile['usrLogin']]][] = $datas;
            }
        }
        return $habiList;
    }

    /**
     * Parcourt les lignes, contrôle l'utilisateur, son profil, les filtres et fait la mise à jour
     *
     * @param array $lines
     *
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function processUsersHabilitations(array $lines)
    {
        $user = null;
        $values = null;
        $profile = null;
        foreach ($lines as $key => &$values) {
            $values['error'] = [];
            if ($this->nbColsImportHabiFile != count($values) - 1) {
                // Incohérence entre le nombre de colonnes attendues et le nombre de colonnes obtenues
                $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_INCORRECT_STRUCTURE;
                continue;
            }
            if ($key == 0) {
                // Recherche le login
                $user = $this->userRepository->find($values[$this->colsImportHabiFile['usrLogin']]);
                if ($user instanceof UsrUsers) {
                    // Recherche le(s) profil(s)
                    $profile = $this->getUserProfile($user);
                }
            }
            if (!($user instanceof UsrUsers) || !$this->hasUserCorrectNameSurname($values, $user)) {
                // Utilisateur non trouvé, nom ou prénom erroné(s)
                $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_WRONG_LOGIN;
            }
            if (!isset($profile['value'])) {
                // Erreur sur le profil
                $values['error'] = array_merge($values['error'], $profile['error']);
                continue;
            }
            if (isset($profile['error']) && $this->checkUserHasFilters($values, $profile['error'])) {
                // Erreur sur les filtres: l'utilisateur a certainement déjà des filtres
                continue;
            }
            // Mise à jour des filtres sur le profil
            $profileUpdated = $this->updateUserFiltersByProfile($values, $profile);
            if (isset($values['error'])) {
                continue;
            } else {
                $profile = $profileUpdated;
            }
        }
        // Mise à jour des droits et accès
        $authorizations = $this->updateUserPermissions($values);
        if (!isset($values['error'])) {
            $this->persistUserProfile(
                $user,
                $profile,
                ['usrAuthorizations' => $authorizations]
            );
        }
        return $lines;
    }

    /**
     * Récupère le profil et l'utilisateur et ses éventuels filtres
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    private function getUserProfile(UsrUsers $user)
    {
        $profile = [];
        // Charge les profils de l'utilisateur
        $list = $this->userManager->loadProfiles($user);
        $count = count($list);
        if (0 == $count) {
            // Pas de profil, on abandonne!
            $profile['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_MISSING_PROFILE;
        } elseif (1 < $count) {
            // Plus de 1 profil, on abandonne!
            $profile['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_MULTIPLE_PROFILES;
        } else {
            // Récupère le profil et les filtres applicatif et sur population
            $profile['value'] = $list[0];
            if (isset($profile['value']['proFiltresApplicatifs']) && $profile['value']['proFiltresApplicatifs']) {
                $profile['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_ALREADY_DEFINED;
            }
            if (isset($profile['value']['proFiltresPopulations']) && $profile['value']['proFiltresPopulations']) {
                $profile['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_POPULATION_FILTER_ALREADY_DEFINED;
            }
        }
        return $profile;
    }

    /**
     * Vérifie la cohérence du nom et prénom de l'utilisateur
     *
     * @param array $values
     * @param UsrUsers $user
     *
     * @return bool
     */
    private function hasUserCorrectNameSurname(array $values, UsrUsers $user)
    {
        if ($values[$this->colsImportHabiFile['usrNom']] != $user->getUsrNom()
            || $values[$this->colsImportHabiFile['usrPrenom']] != $user->getUsrPrenom()
        ) {
            return false;
        }
        return true;
    }

    /**
     * Vérifie l'existance de filtres pour l'utilisateur et qu'ils ne sont pas vides
     *
     * @param array $values
     * @param $errors
     *
     * @return bool
     */
    private function checkUserHasFilters(array &$values, $errors)
    {
        $error = false;
        if (in_array(UsrUsers::ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_ALREADY_DEFINED, $errors)
            && $values[$this->colsImportHabiFile['filtreApplicatif']]
        ) {
            // Filtre applicatif déjà existant sur le profil
            $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_ALREADY_DEFINED;
            $error = true;
        }
        if (in_array(UsrUsers::ERR_HABILITATION_IMPORT_POPULATION_FILTER_ALREADY_DEFINED, $errors)
            && $values[$this->colsImportHabiFile['filtrePopulation']]
        ) {
            // Filtre sur population déjà existant sur le profil
            $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_POPULATION_FILTER_ALREADY_DEFINED;
            $error = true;
        }
        return $error;
    }

    /**
     * Recherche les Id associés aux libellés des filtres et prépare la mise à jour
     *
     * @param array $values
     * @param array $profile
     *
     * @return bool
     */
    private function updateUserFiltersByProfile(array &$values, array $profile)
    {
        if ($applicatifFilter = $values[$this->colsImportHabiFile['filtreApplicatif']]) {
            // Si le filtre applicatif est défini dans le fichier, on cherche son libellé
            if (!$filter = $this->profilApplicatifRepository->findOneBy(
                ['pdaLibelle' => $applicatifFilter]
            )
            ) {
                // Libellé non trouvé
                $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_DOES_NOT_EXIST;
            } else {
                // Récupération de l'Id
                $profile['value']['proFiltresApplicatifs'][] = $filter->getPdaId();
            }
        }
        if ($populationFilter = $values[$this->colsImportHabiFile['filtrePopulation']]) {
            // Si le filtre sur population est défini dans le fichier, on cherche son libellé
            if (!$filter = $this->profilPopulationRepository->findOneBy(
                ['pdhLibelle' => $populationFilter]
            )
            ) {
                // Libellé non trouvé
                $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_POPULATION_FILTER_DOES_NOT_EXIST;
            } else {
                // Récupération de l'Id
                $profile['value']['proFiltresPopulations'][] = $filter->getPdhId();
            }
        }
        return $profile;
    }

    /**
     * Prépare la mise à jour des droits et accès de l'utilisateur
     *
     * @param array $values
     *
     * @return mixed
     */
    private function updateUserPermissions(array &$values)
    {
        $authorizations = [];
        foreach (array_keys($this->colsImportHabiFile) as $habilitation) {
            $value = $values[$this->colsImportHabiFile[$habilitation]];
            if ('right' == substr($habilitation, 0, 5)) {
                if (!preg_match('/^N|(R|(RW|RWD))$/', $value)) {
                    // La saisie n'est pas correcte
                    $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_RIGHT_WRONG_VALUE;
                    break;
                } else {
                    // On met en forme les autorisations pour les insérer dans la base
                    $authorizations = $this->setUserRight($habilitation, $value, $authorizations);
                }
            } elseif ('access' == substr($habilitation, 0, 6)) {
                if ('O' != $value && 'N' != $value) {
                    // La saisie n'est pas correcte
                    $values['error'][] = UsrUsers::ERR_HABILITATION_IMPORT_RIGHT_WRONG_VALUE;
                    break;
                } else {
                    $authorizations[$habilitation] = 'O' == $value ? true : false;
                }
            }
        }
        return $authorizations;
    }

    /**
     * Met à jour les permissions de l'utilisateur en fonction de la valeur saisie
     *
     * @param $habilitation
     * @param $value
     * @param array $authorizations
     *
     * @return array
     */
    private function setUserRight($habilitation, $value, array $authorizations)
    {
        $authorizations[$habilitation] = [];
        $permissions = str_split($value);
        foreach (['R', 'W', 'D'] as $permission) {
            if (in_array($permission, $permissions)) {
                $authorizations[$habilitation][$permission] = true;
            }
        }
        return $authorizations;
    }

    /**
     * Fait la mise à jour de l'utilisateur
     *
     * @param UsrUsers $user
     * @param array $profile
     * @param array $authorizations
     */
    private function persistUserProfile(UsrUsers $user, array $profile, array $authorizations)
    {
        $formImportFile = $this->container->get('form.factory')->create(UsrUsersType::class, $user);
        $formImportFile->submit($authorizations, false);
        if ($formImportFile->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->userManager->saveProfiles($user, [$profile['value']]);
        }
    }

    /**
     * Met en forme le rapport pour chaque ligne et complète les propriétées pour la présentation du rapport
     *
     * @param array $lines
     * @param $report
     *
     * @return mixed
     */
    private function generateHabilitationReportLinesLogin(array $lines, $report)
    {
        // Parcours de chaque ligne pour un login
        foreach ($lines as $values) {
            $errors = array_pop($values);
            // Ligne avec erreur ?
            if (count($errors)) {
                $values[] = EnumLabelEtatReportType::KO_ETAT_REPORT;
                // Traduction des éventuelle(s) erreur(s)
                array_walk($errors, function (&$error) {
                    $error = $this->dictionaryManager->getParameter($error);
                });
                $values[] = implode(' ', $errors);
                // Incrémente les lignes en erreur
                $report['ihaErreur'] += 1;
            } else {
                $values[] = EnumLabelEtatReportType::OK_ETAT_REPORT;
                // Incrémente les lignes en succès
                $report['ihaSucces'] += 1;
            }
            $report['ihaRapport']['rapFichier'][] = $values;
            // Incrémente le nombre de lignes traitées
            $report['ihaTraite'] += 1;
        }
        return $report;
    }

    /**
     * Initialisation des propriétés du rapport
     *
     * @param array $report
     *
     * @return array
     */
    private function initializeReportProperties(array $report)
    {
        $report['ihaRapport']['rapEtat'] = $report['ihaErreur'] ?
            EnumLabelEtatReportType::KO_ETAT_REPORT : EnumLabelEtatReportType::OK_ETAT_REPORT;
        $report['ihaRapport']['rapTypeRapport'] = EnumLabelTypeRapportType::HABILITATION_TYPE;
        $report['ihaRapport']['rapLibelleFic'] = 'report.csv';
        // On sérialise le tableau
        $report['ihaRapport']['rapFichier'] = serialize($report['ihaRapport']['rapFichier']);
        return $report;
    }

    /**
     * Valide le formulaire et enregistre le rapport
     *
     * @param array $values
     *
     * @return bool
     */
    private function persistHabilitationReport(array $values)
    {
        $habilitationImport = new IhaImportHabilitation();
        $formHabilitation = $this->container->get('form.factory')
            ->create(IhaImportHabilitationType::class, $habilitationImport);
        $formHabilitation->submit($values, false);
        if ($formHabilitation->isValid()) {
            $this->entityManager->persist($habilitationImport);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    /**
     * Récupère la liste des imports des habilitations
     *
     * @return mixed
     */
    public function retrieveHabilitationImportList()
    {
        return $this->entityManager->getRepository('ApiBundle:IhaImportHabilitation')
            ->findHabilitationImportList();
    }

    /**
     * Récupère la liste des utilisateurs qui n'ont pas ou ont des habilitations incomplètes avec leurs droits
     *
     * @return array
     */
    public function retrieveHabilitationModel()
    {
        $results = [];
        // Récupère les profils avec les filtres
        $profiles = $this->profilRepository->findUsersAllProfileFilters();
        foreach ($profiles as $profile) {
            // Ré-organisation dans un tableau indicé par le login
            if (!isset($results[$profile['usrLogin']])) {
                $results[$profile['usrLogin']]['infos'] = [
                    'usrNom' => $profile['usrNom'],
                    'usrPrenom' => $profile['usrPrenom']
                ];
                $results[$profile['usrLogin']]['authorizations'] = $this->getAuthorizationsModel($profile);
            }
            // Création du profil
            if (!isset($results[$profile['usrLogin']]['profiles'])) {
                $results[$profile['usrLogin']]['profiles'][$profile['proId']] = [];
            }
            // Création de la dimension des filtres applicatifs
            if (!isset($results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdaLibelle'])) {
                $results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdaLibelle'] = [];
            }
            // Création de la dimension des filtres sur population
            if (!isset($results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdhLibelle'])) {
                $results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdhLibelle'] = [];
            }
            if ($profile['pdaLibelle']) {
                // AJout du filtre applicatif
                $results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdaLibelle'][] = $profile['pdaLibelle'];
            }
            if ($profile['pdhLibelle']) {
                // Ajout du filtre sur population
                $results[$profile['usrLogin']]['profiles'][$profile['proId']]['pdhLibelle'][] = $profile['pdhLibelle'];
            }
        }
        // Création du fichier et envoi au navigateur
        $this->exportExcelManager->create();
        $this->exportExcelManager->fromSimpleArray($this->getHabilitationModelToExport($results));
        return $this->exportExcelManager->export('export_habilitation.csv');
    }

    /**
     * Convertit les droits en R, RW ou RWD ou les accès en 'O' ou 'N'
     *
     * @param array $authorizations
     *
     * @return array
     */
    private function getAuthorizationsModel(array $authorizations)
    {
        $results = [];
        foreach ($authorizations as $habi => $dec) {
            if ('usrRight' == substr($habi, 0, 8) || 'usrAccess' == substr($habi, 0, 9)) {
                $habi = lcfirst(substr($habi, 3));
                // Conversion des habilitations en R => true, W => false, d => false
                $authorizations = $this->securityManager
                    ->convertHabilitationToPermissions($habi, $dec);
                if ('right' == substr($habi, 0, 5)) {
                    // C'est un droit
                    $results[$habi] = '';
                    foreach (array_keys($authorizations) as $permission) {
                        // Si true, ajout du droit
                        if ($authorizations[$permission]) {
                            $results[$habi] .= $permission;
                        }
                    }
                    if (!$results[$habi]) {
                        // Si pas de droit, on affiche N
                        $results[$habi] = 'N';
                    }
                } else {
                    // Conversion des accès en O ou N
                    $results[$habi] = (bool)$authorizations ? 'O' : 'N';
                }
            }
        }
        return $results;
    }

    /**
     * Renvoie le tableau prêt à être exporté en CSV
     *
     * @param array $model
     *
     * @return array
     */
    private function getHabilitationModelToExport(array $model)
    {
        // Ligne d'en-têtes
        $export[] = $this->headersLabelFile;
        foreach ($model as $login => $values) {
            $line = [
                $this->colsImportHabiFile['usrLogin'] => $login,
                $this->colsImportHabiFile['usrNom'] => $values['infos']['usrNom'],
                $this->colsImportHabiFile['usrPrenom'] => $values['infos']['usrPrenom']
            ];
            // Vérifie que l'utilisateur a uniquement 1 profil
            if (1 != count($values['profiles'])) {
                continue;
            }
            // Récupère les filtres
            $filters = array_values($values['profiles'])[0];
            if (count($filters['pdaLibelle']) && count($filters['pdhLibelle'])) {
                // Les 2 filtres sont renseignés, on abandonne!
                continue;
            }
            $toCreate = $this->createFilterFile($filters, $line);
            // Ajout des droits et accès
            foreach ($values['authorizations'] as $habi => $value) {
                $line[$this->colsImportHabiFile[$habi]] = $value;
            }
            // Ajout de la ligne
            $export[] = $line;
            // Création des lignes supplémentaires si plusieurs filtres
            while (0 < $toCreate) {
                $toCreate = $this->createFilterFile($filters, $line);
                $export[] = $line;
            }
        }
        return $export;
    }

    /**
     * Crée un filtre dans le fichier CSV
     *
     * @param array $filters
     * @param array $line
     *
     * @return int
     */
    private function createFilterFile(array &$filters, array &$line)
    {
        // Calcul du nombre de filtres applicatifs
        $nbApplicatifFilter = count($filters['pdaLibelle']);
        // Calcul du nombre de filtres sur population
        $nbPopulationFilter = count($filters['pdhLibelle']);
        if ($nbPopulationFilter) {
            // Ajout du filtre sur population sur la ligne
            $line[$this->colsImportHabiFile['filtrePopulation']] = array_shift($filters['pdhLibelle']);
            $line[$this->colsImportHabiFile['filtreApplicatif']] = '';
        } else {
            // Ajout du filtre applicatif sur la ligne
            $line[$this->colsImportHabiFile['filtreApplicatif']] = array_shift($filters['pdaLibelle']);
            $line[$this->colsImportHabiFile['filtrePopulation']] = '';
        }
        // Renvoie du nombre de lignes à créer
        return ($nbApplicatifFilter ? $nbApplicatifFilter : $nbPopulationFilter) - 1;
    }

    /**
     * Renvoie les traductions des en-têtes de rapport CSV
     *
     * @return array
     */
    private function getHeadersLabelFile()
    {
        return [
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_LOGIN),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_SURNAME),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_FIRSTNAME),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_POPULATION_FILTER),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_APPLICATIVE_FILTER),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_DOC_SEARCH),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_ORDER),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_LIFE_CYCLE),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_MANAGE_USERS),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_DOCUMENT_ANNOTATION),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_RIGHT_FOLDER_ANNOTATION),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_ACCESS_CEL_EXPORT),
            $this->dictionaryManager->getParameter(UsrUsers::HEADER_HABILITATION_EXPORT_ACCESS_UNIT_IMPORT)
        ];
    }
}

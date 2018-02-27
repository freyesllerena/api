<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\RapRapport;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReportManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UsrUsers
     */
    private $user;

    /**
     * @var ExportExcelManager
     */
    private $exportExcelManager;

    /**
     * @var DictionaryManager
     */
    private $dictionaryManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->exportExcelManager = $container->get('api.manager.export_excel');
        $this->dictionaryManager = $container->get('api.manager.dictionnaire');
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $userToken = $container->get('security.token_storage')->getToken();
        /** @var UserToken $userToken */
        $this->user = $userToken->getUser();
    }

    /**
     * Récupère un rapport par son Id
     *
     * @param $rapId
     *
     * @return mixed
     */
    public function getReportById($rapId)
    {
        return $this->entityManager->getRepository('ApiBundle:RapRapport')->find($rapId);
    }

    /**
     * Vérifie que l'utilisateur a accès au rapport
     *
     * @param RapRapport $report
     *
     * @return bool
     */
    public function isCorrectUser(RapRapport $report)
    {
        if ($rapUser = $report->getRapUser()) {
            return $rapUser->getUsrLogin() == $this->user->getUsrLogin();
        }
        return true;
    }

    /**
     * Récupère la liste des rapports de l'import de masse
     *
     * @return mixed
     */
    public function retrieveMassImportList()
    {
        return $this->entityManager->getRepository('ApiBundle:RapRapport')
            ->findMassImportList();
    }

    /**
     * Récupère un rapport à partir de son utilisateur et de son type
     *
     * @param $reportType
     *
     * @return mixed
     */
    public function retieveOneReportByUserAndType($reportType)
    {
        return $this->entityManager->getRepository('ApiBundle:RapRapport')
            ->findOneBy([
                'rapTypeRapport' => $reportType,
                'rapUser' => $this->user
            ]);
    }

    /**
     * Vérifie que le rapport demandé est un rapport de recherche
     *
     * @param RapRapport $report
     *
     * @return bool
     */
    public function isSearchReportType(RapRapport $report)
    {
        return EnumLabelTypeRapportType::RECHERCHE_TYPE == $report->getRapTypeRapport();
    }

    /**
     * Vérifie que le(s) code(s) source(s) pour les rapports CEL sont valides
     *
     * @param array $sourceCodes
     *
     * @return bool
     */
    public function checkCelSourceCodes(array $sourceCodes)
    {
        $validCodes = $this->container->get('api.manager.document')
            ->retrieveFieldSearch([
                'field' => 'ifpIdAlphanum1',
                'distinct' => true
            ]);
        return !count(array_diff($sourceCodes, $validCodes)) ? true : false;
    }

    /**
     * Renvoie un export d'un rapport des flux CEL
     *
     * @param array $datas
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Exception
     */
    public function getReportCel(array $datas)
    {
        if (!$datas['source']) {
            $datas['source'] = ['CEL', 'VIS', 'VISION'];
        }
        $result = $this->container->get('api.manager.document')->retrieveCelList($datas);
        if ('periode' == $datas['typeRapport']) {
            $this->createReportByPeriod();
            $reorganized = $this->constructReportByPeriod($result);
            if (count($reorganized)) {
                $this->fillCellsReportByPeriod($reorganized);
            } else {
                $this->setNoDataReport();
            }
        } else {
            $this->createReportByType();
            $reorganized = $this->constructReportByType($result);
            if (count($reorganized)) {
                $this->fillCellsReportByType($reorganized);
            } else {
                $this->setNoDataReport();
            }
        }
        return $this->exportExcelManager->export('export_CEL_report_' . $this->user->getUsrLogin() . '.xlsx');
    }

    /**
     * Affiche un message dans la première cellule informant qu'il n'y a aucune donnée à afficher
     *
     * @throws \Exception
     */
    private function setNoDataReport()
    {
        $format['setOnCells'][][]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
        $this->exportExcelManager->fromCustomArray(
            [
                [$this->dictionaryManager->getParameter(RapRapport::ERR_RAP_EXPORT_NO_DATA)]
            ],
            $format
        );
    }

    /**
     * Crée le fichier Excel des flux CEL par type de document
     */
    private function createReportByType()
    {
        $this->exportExcelManager->create([
            'title' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_TYPE_EXPORT_TITLE),
            'subject' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_TYPE_EXPORT_DESCRIPTION),
            'description' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_TYPE_EXPORT_DESCRIPTION),
            'sheet' => [
                'title' => $this->dictionaryManager
                    ->getParameter(RapRapport::PROPERTIE_RAP_CEL_EXPORT_SHEET_TITLE)
            ]
        ]);
    }

    /**
     * Réorganise les données de façon à créer le rapport des flux CEL par type de document
     *
     * @param $result
     *
     * @return array
     */
    private function constructReportByType(array $result)
    {
        $reorganized = [];
        if (count($result)) {
            // Initialisation
            $reorganized['startPeriod'] = $result[0]['ifpIdPeriodePaie'];
            $reorganized['endPeriod'] = $reorganized['startPeriod'];
            foreach ($result as $values) {
                $period = $values['ifpIdPeriodePaie'];
                $documentLabel = $values['ifpIdLibelleDocument'];
                if ($period < $reorganized['startPeriod']) {
                    // Ajoute la période de début
                    $reorganized['startPeriod'] = $period;
                }
                if ($period > $reorganized['endPeriod']) {
                    // Ajoute la période de fin
                    $reorganized['endPeriod'] = $period;
                }
                if (!isset($reorganized['datas'][$documentLabel][$values['ifpNumdtr']][$values['ifpModedt']])) {
                    // Initialise le tableau
                    $reorganized['datas'][$documentLabel][$values['ifpNumdtr']][$values['ifpModedt']] = 0;
                }
                // Compte le nombre de documents
                $reorganized['datas'][$documentLabel][$values['ifpNumdtr']][$values['ifpModedt']] += 1;
            }
        }
        return $reorganized;
    }

    /**
     * Construit le contenu du fichier Excel des flux CEL classés par type de document
     *
     * @param array $reorganized
     *
     * @throws \Exception
     */
    private function fillCellsReportByType(array $reorganized)
    {
        $headers = [];
        $headers[] = [
            str_replace(
                ['__startYear__', '__startMonth__', '__endYear__', '__endMonth__'],
                [
                    substr($reorganized['startPeriod'], 0, 4),
                    substr($reorganized['startPeriod'], -2),
                    substr($reorganized['endPeriod'], 0, 4),
                    substr($reorganized['endPeriod'], -2)
                ],
                $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_TYPE_STATS)
            )
        ];
        $headers[] = [
            $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_TYPE_DOCUMENT_NAME),
            $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_TYPE_PGM),
            $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_DTR),
            $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_TYPE_NUMBER)
        ];
        $values = [];
        foreach ($reorganized['datas'] as $documentLabel => $dtrNums) {
            foreach ($dtrNums as $dtrNum => $dtModes) {
                foreach ($dtModes as $dtMode => $count) {
                    $values[] = [
                        $documentLabel,
                        $dtMode,
                        $dtrNum,
                        $count
                    ];
                }
            }
        }
        $format = [];
        // La ligne 1 est une ligne d'en-têtes
        $format['setOnRows'][1]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
        $format['setOnCells'][][]['applyFromArray'] = $format['setOnRows'][1]['applyFromArray'];
        // La colonne Numdtr est une colonne de chaîne de caractères
        $format['setOnColumns'][2]['setTypeAs'] = \PHPExcel_Cell_DataType::TYPE_STRING;
        // Ajout du contenu des cellules
        $this->exportExcelManager->fromCustomArray(
            array_merge($headers, $values),
            $format
        );
    }

    /**
     * Crée le fichier Excel des flux CEL par période
     */
    private function createReportByPeriod()
    {
        $this->exportExcelManager->create([
            'title' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_PERIOD_EXPORT_TITLE),
            'subject' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_PERIOD_EXPORT_DESCRIPTION),
            'description' => $this->dictionaryManager
                ->getParameter(RapRapport::PROPERTIE_RAP_CEL_PERIOD_EXPORT_DESCRIPTION),
            'sheet' => [
                'title' => $this->dictionaryManager
                    ->getParameter(RapRapport::PROPERTIE_RAP_CEL_EXPORT_SHEET_TITLE)
            ]
        ]);
    }

    /**
     * Réorganise les données de façon à créer le rapport des flux CEL par période
     *
     * @param $result
     *
     * @return array
     */
    private function constructReportByPeriod(array $result)
    {
        $reorganized = [];
        foreach ($result as $values) {
            $period = $values['ifpIdPeriodePaie'];
            $year = substr($period, 0, 4);
            $month = substr($period, -2);
            if (!isset($reorganized['dtrList'])) {
                $reorganized['dtrList'] = [];
            }
            if (!in_array($values['ifpNumdtr'], $reorganized['dtrList'])) {
                // Ajoute la DTR dans la liste
                $reorganized['dtrList'][] = $values['ifpNumdtr'];
            }
            if (!isset($reorganized['values'][$year][$values['ifpNumdtr']]['total'])) {
                $reorganized['values'][$year][$values['ifpNumdtr']]['total'] = 0;
            }
            // Compte le nombre de DTR pour l'année en cours
            $reorganized['values'][$year][$values['ifpNumdtr']]['total'] += 1;
            if (!isset($reorganized['values'][$year][$values['ifpNumdtr']]['month'][$month])) {
                $reorganized['values'][$year][$values['ifpNumdtr']]['month'][$month] = 0;
            }
            // Compte le nombre de DTR pour l'année et le mois en cours
            $reorganized['values'][$year][$values['ifpNumdtr']]['month'][$month] += 1;
        }
        return $reorganized;
    }

    /**
     * Construit le contenu du fichier Excel des flux CEL classés par période
     *
     * @param array $reorganized
     *
     * @throws \Exception
     */
    private function fillCellsReportByPeriod(array $reorganized)
    {
        $months = $this->getMonthsTranslation();
        $headers = array_merge(
            [$this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_DTR)],
            $months,
            [$this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_EXPORT_TOTAL)]
        );

        $values = [];
        $line = 0;
        // Création d'un tableau pour chaque année
        foreach (array_keys($reorganized['values']) as $year) {
            // Ajout des en-têtes de colonnes
            $values[$line][] = $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_EXPORT_YEAR) . ' ' . $year;
            $format['setOnCells'][$line][]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
            $values[++$line] = $headers;
            $format['setOnRows'][$line]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
            // Tri des DTR par ordre croissant
            ksort($reorganized['values'][$year]);
            // Parcours de chaque année
            foreach ($reorganized['values'][$year] as $dtrNum => $counts) {
                // Ajout de la DTR sur une nouvelle ligne
                $values[++$line][] = $dtrNum;
                // Parcours de chaque mois
                for ($cpt = 1; $cpt <= count($months); $cpt++) {
                    $month = str_pad($cpt, 2, 0, STR_PAD_LEFT);
                    // Vérifie qu'il y a une valeur pour le mois
                    if (isset($counts['month'][$month])) {
                        $values[$line][] = $counts['month'][$month];
                    } else {
                        $values[$line][] = 0;
                    }
                }
                // Ajout du total en fin de ligne
                $values[$line][] = $counts['total'];
            }
            // Calcule les sous-totaux de chaque colonne
            $values = $this->calculateTotalsByPeriodLine(
                $values,
                ++$line,
                count($reorganized['values'][$year]),
                count($months)
            );
            $line += 2;
        }
        $line += 2;
        // Ajout du tableau de périodicité globale
        $years = array_keys($reorganized['values']);
        $headers = array_merge(
            [$this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_DTR)],
            $years,
            [$this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_EXPORT_TOTAL)]
        );
        $values[$line][] = $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_CEL_EXPORT_PERIOD_PERIODICITY);
        $format['setOnCells'][$line][]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
        $values[++$line] = $headers;
        $format['setOnRows'][$line]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
        // Tri des valeurs par ordre croissant
        sort($reorganized['dtrList']);
        // Parcours de chaque DTR
        foreach ($reorganized['dtrList'] as $dtrNum) {
            // Ajout de la DTR sur une nouvelle ligne
            $values[++$line][] = $dtrNum;
            $nbDtrOnYears = 0;
            // Parcours de chaque année
            foreach ($years as $year) {
                if (isset($reorganized['values'][$year][$dtrNum]['total'])) {
                    $values[$line][] = $reorganized['values'][$year][$dtrNum]['total'];
                    $nbDtrOnYears += $reorganized['values'][$year][$dtrNum]['total'];
                } else {
                    $values[$line][] = 0;
                }
            }
            // Ajout du total en fin de ligne
            $values[$line][] = $nbDtrOnYears;
        }
        // Calcule les sous-totaux de chaque colonne
        $values = $this->calculateTotalsByPeriodLine(
            $values,
            ++$line,
            count($reorganized['dtrList']),
            count($reorganized['values'])
        );
        $format['setOnColumns'][]['setTypeAs'] = \PHPExcel_Cell_DataType::TYPE_STRING;

        $this->exportExcelManager->fromCustomArray($values, $format);
    }

    /**
     * Récupère les traductions des mois
     *
     * @return array
     */
    private function getMonthsTranslation()
    {
        return [
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_JANUARY),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_FEBRUARY),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_MARCH),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_APRIL),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_MAY),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_JUNE),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_JULY),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_AUGUST),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_SEPTEMBER),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_OCTOBER),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_NOVEMBER),
            $this->dictionaryManager->getParameter(RapRapport::MONTH_RAP_DECEMBER)
        ];
    }

    /**
     * Calcule les sous-totaux de chaque tableau sur la dernière ligne pour les exports de flux par période
     *
     * @param array $values
     * @param $line
     * @param $nbLines
     * @param $nbColumns
     *
     * @return array
     */
    private function calculateTotalsByPeriodLine(array $values, $line, $nbLines, $nbColumns)
    {
        $activeSheet = $this->exportExcelManager->getActiveSheet();
        $values[$line][] = $this->dictionaryManager->getParameter(RapRapport::HEADER_RAP_EXPORT_TOTAL);
        for ($cpt = 0; $cpt <= $nbColumns; $cpt++) {
            $values[$line][] = '=SUM(' .
                $activeSheet->getCellByColumnAndRow(
                    $cpt + 1,
                    $line - $nbLines + 1
                )->getCoordinate() . ':' . $activeSheet->getCellByColumnAndRow(
                    $cpt + 1,
                    $line
                )->getCoordinate() . ')';
        }
        return $values;
    }
}

<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\RapRapport;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ExportExcelManager
 * @package ApiBundle\Manager
 */
class ExportExcelManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Liuggio\ExcelBundle\Factory
     */
    private $phpExcel;

    /**
     * @var \PHPExcel
     */
    private $phpExcelObject;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $exportType;

    /**
     * @var \PHPExcel_Worksheet
     */
    private $activeSheet;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->phpExcel = $this->container->get('phpexcel');
    }

    /**
     * Initialise la création d'un fichier
     *
     * @param array $properties
     */
    public function create($properties = [])
    {
        $this->phpExcelObject = $this->container->get('phpexcel')
            ->createPHPExcelObject();
        // Définition des propriétés
        $this->setProperties($properties);
        // Fixe la 1ère feuille comme feuille par défaut
        $this->activeSheet = $this->phpExcelObject->getActiveSheet();

        // Définition du titre de la feuille
        if (!empty($properties['sheet']['title'])) {
            $this->activeSheet->setTitle($properties['sheet']['title']);
        }
    }

    /**
     * Traite un tableau multidimensionnel sans formatage
     *
     * @param array $source
     * @param null $nullValue
     * @param string $startCell
     *
     * @throws \PHPExcel_Exception
     */
    public function fromSimpleArray(array $source, $nullValue = null, $startCell = 'A1')
    {
        if (!$this->activeSheet instanceof \PHPExcel_Worksheet) {
            throw new \PHPExcel_Exception('You must first call the creation method before processing an array.');
        }
        $this->activeSheet->fromArray($source, $nullValue, $startCell);
    }

    /**
     * Traite un tableau multidimensionnel avec formatage des cellules, colonnes et lignes
     *
     * @param array $source
     * @param array $format
     *
     * @throws \PHPExcel_Exception
     */
    public function fromCustomArray(array $source, array $format)
    {
        if (!$this->activeSheet instanceof \PHPExcel_Worksheet) {
            throw new \PHPExcel_Exception('You must first call the creation method before processing an array.');
        }
        $this->fillCells($source, $format);
    }

    /**
     * Renvoie la feuille courante
     *
     * @return mixed
     */
    public function getActiveSheet()
    {
        return $this->activeSheet;
    }

    /**
     * Renvoie le fichier au navigateur
     *
     * @param string $filename
     *
     * @return StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function export($filename = 'export.xlsx')
    {
        if (!$this->phpExcelObject instanceof \PHPExcel) {
            throw new \PHPExcel_Exception('You must first call the creation method before calling the export method.');
        }
        $this->filename = $filename;
        // Définition du Writer
        $objWriter = $this->prepareWriter();
        // Renvoie de la réponse
        $response = $this->phpExcel->createStreamedResponse($objWriter);
        // Ajout des headers
        $this->setHeaders($response);

        return $response;
    }

    /**
     * Sauvegarde un fichier Excel dans un répertoire
     *
     * @param $pathName
     *
     * @throws \PHPExcel_Exception
     */
    public function save($pathName)
    {
        if (!$this->phpExcelObject instanceof \PHPExcel) {
            throw new \PHPExcel_Exception('You must first call the creation method before calling the save method.');
        }
        $this->filename = basename($pathName);
        // Création du Writer
        $objWriter = $this->prepareWriter();
        // Sauvegarde le fichier dans le répertoire
        $objWriter->save($pathName);
    }

    /**
     * Lecture d'un fichier Excel
     *
     * @param $pathName
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function read($pathName)
    {
        $this->filename = basename($pathName);
        $this->getTypeByExtension();
        if (!$this->isExcel2007Export()) {
            throw new \PHPExcel_Exception('Extension file is not supported.');
        }
        // Création du Reader
        $objReader = new \PHPExcel_Reader_Excel2007();
        // Chargement du fichier Excel
        $this->phpExcelObject = $objReader->load($pathName);
    }

    /**
     * Récupère le style à appliquer aux en-têtes
     *
     * @return array
     */
    public function getStyleHeader()
    {
        return [
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'f2f2f2'
                ]
            ]
        ];
    }

    /**
     * Ajoute des propriétés au fichier
     *
     * @param array $properties
     */
    private function setProperties(array $properties)
    {
        // Récupération des propriétés
        $objProperties = $this->phpExcelObject->getProperties();
        $objProperties
            ->setCreator('ADP')
            ->setLastModifiedBy('ADP')
            ->setCategory(
                $this->container->get('api.manager.dictionnaire')
                    ->getParameter(RapRapport::PROPERTIE_RAP_EXCEL_CATEGORY)
            );
        if (!empty($properties['title'])) {
            // Définition du titre
            $objProperties->setTitle($properties['title']);
        }
        if (!empty($properties['subject'])) {
            // Définition du sujet
            $objProperties->setSubject($properties['subject']);
        }
        if (!empty($properties['description'])) {
            // Définition de la description
            $objProperties->setDescription($properties['description']);
        }
        if (!empty($properties['keywords'])) {
            // Définition des mots-clefs
            $objProperties->setKeywords($properties['keywords']);
        }
    }

    /**
     * Prépare le Writer (Excel5, Excel2007 ou CSV)
     *
     * @return \PHPExcel_Writer_CSV|\PHPExcel_Writer_IWriter
     * @throws \PHPExcel_Exception
     */
    private function prepareWriter()
    {
        if (!$this->exportType) {
            $this->getTypeByExtension();
        }
        // Création du Writer
        $objWriter = $this->phpExcel->createWriter($this->phpExcelObject, $this->exportType);
        if ($this->isCSVExport()) {
            /** @var \PHPExcel_Writer_CSV $objWriter */
            $this->defineCSVParameters($objWriter);
        }
        return $objWriter;
    }

    /**
     * Défini les paramètres pour un export CSV
     *
     * @param \PHPExcel_Writer_CSV $objWriter
     */
    private function defineCSVParameters(\PHPExcel_Writer_CSV $objWriter)
    {
        $objWriter->setDelimiter(';');
        $objWriter->setEnclosure('');
        $objWriter->setSheetIndex(0);
        $objWriter->setUseBOM(true);
    }

    /**
     * Récupère le type de Writer par rapport à l'extension
     *
     * @throws \PHPExcel_Exception
     */
    private function getTypeByExtension()
    {
        list(, $extension) = explode('.', $this->filename);
        switch ($extension) {
            case 'xls':
                $this->exportType = 'Excel5';
                break;
            case 'xlsx':
                $this->exportType = 'Excel2007';
                break;
            case 'csv':
                $this->exportType = 'CSV';
                break;
            default:
                throw new \PHPExcel_Exception('Unable to determine type file.');
        }
    }

    /**
     * Vérifie que le type demandé est CSV
     *
     * @return bool
     */
    private function isCSVExport()
    {
        return 'CSV' == $this->exportType;
    }

    /**
     * Vérifie que le type demandé est Excel2007
     *
     * @return bool
     */
    private function isExcel2007Export()
    {
        return 'Excel2007' == $this->exportType;
    }

    /**
     * Fixe les headers avant envoi au navigateur
     *
     * @param $response
     */
    private function setHeaders($response)
    {
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->filename
        );
        $response->headers
            ->set('Content-Type', 'text/' . ($this->isCSVExport() ? 'csv' : 'vnd.ms-excel') . '; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
    }

    /**
     * Traite le tableau multidimensionnel, ajoute un style sur les cellules et formate les colonnes
     *
     * @param $values
     * @param $format
     *
     * @throws \PHPExcel_Exception
     */
    private function fillCells($values, $format)
    {
        $maxColumns = 0;
        $nbRows = count($values);
        foreach ($values as $nRow => $datas) {
            $nColumn = 0;
            foreach ($datas as $value) {
                $propertiesCell = $this->getPropertiesCellAs($format, $nRow, $nColumn);
                if (isset($propertiesCell['setTypeAs']) && $setTypeAs = $propertiesCell['setTypeAs']) {
                    $this->activeSheet->setCellValueExplicitByColumnAndRow($nColumn, $nRow + 1, $value, $setTypeAs);
                } else {
                    $this->activeSheet->setCellValueByColumnAndRow($nColumn, $nRow + 1, $value);
                }
                // On applique le style défini sur la cellule
                $this->applyPropertiesFromArray(
                    $this->getCellValueProperties($format, $nRow, $nColumn),
                    $nColumn,
                    $nRow + 1
                );
                $nColumn += 1;
            }
            $nbColumns = count($datas);
            // Mémorisation du nombres de colonnes max pour redimensionner correctement chaque colonne
            $maxColumns = $nbColumns > $maxColumns ? $nbColumns : $maxColumns;
            // On applique le style défini sur la ligne
            $this->applyPropertiesFromArray(
                $this->getRowValueProperties($format, $nRow),
                0,
                $nRow + 1,
                $nbColumns - 1,
                $nRow + 1
            );
        }
        for ($nColumn = 0; $nColumn < $maxColumns; $nColumn++) {
            // On ajuste la largeur des colonnes à partir du contenu
            $this->activeSheet->getColumnDimensionByColumn($nColumn)->setAutoSize(true);
            // On applique le style défini sur la colonne
            $this->applyPropertiesFromArray(
                $this->getColumnValueProperties($format, $nColumn),
                $nColumn,
                1,
                $nColumn,
                $nbRows
            );
        }
    }

    /**
     * Applique le style défini à partir d'un tableau formaté de façon à être reconnu par PHPExcel
     *
     * @param array $properties
     * @param $nColumn
     * @param $nRow
     * @param null $nColumn2
     * @param null $nRow2
     *
     * @throws \PHPExcel_Exception
     */
    private function applyPropertiesFromArray(array $properties, $nColumn, $nRow, $nColumn2 = null, $nRow2 = null)
    {
        if (isset($properties['applyFromArray']) && $applyFromArray = $properties['applyFromArray']) {
            $this->activeSheet->getStyleByColumnAndRow($nColumn, $nRow, $nColumn2, $nRow2)->applyFromArray(
                $applyFromArray
            );
        }
    }

    /**
     * Récupère les propriétés d'une cellule aussi bien définie sur une cellule, sur une ligne ou une colonne
     *
     * @param $format
     * @param $pRow
     * @param $pColumn
     *
     * @return array
     */
    private function getPropertiesCellAs($format, $pRow, $pColumn)
    {
        return
            $this->getCellValueProperties($format, $pRow, $pColumn) +
            $this->getRowValueProperties($format, $pRow) +
            $this->getColumnValueProperties($format, $pColumn);
    }

    /**
     * Récupère les propriétés pour une cellule
     *
     * @param $format
     * @param $pRow
     * @param $pColumn
     *
     * @return array
     */
    private function getCellValueProperties($format, $pRow, $pColumn)
    {
        $result = [];
        if (!empty($format['setOnCells'][$pRow][$pColumn])) {
            $result = $format['setOnCells'][$pRow][$pColumn];
        }
        return $result;
    }

    /**
     * Récupère les propriétés pour une ligne
     *
     * @param $format
     * @param $pRow
     *
     * @return array
     */
    private function getRowValueProperties($format, $pRow)
    {
        $result = [];
        if (!empty($format['setOnRows'][$pRow])) {
            $result = $format['setOnRows'][$pRow];
        }
        return $result;
    }

    /**
     * Récupère les propriétés pour une colonne
     *
     * @param $format
     * @param $pColumn
     *
     * @return array
     */
    private function getColumnValueProperties($format, $pColumn)
    {
        $result = [];
        if (!empty($format['setOnColumns'][$pColumn])) {
            $result = $format['setOnColumns'][$pColumn];
        }
        return $result;
    }
}

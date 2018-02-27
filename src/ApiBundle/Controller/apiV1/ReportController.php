<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\RapRapport;
use ApiBundle\Entity\RcsRefCodeSociete;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use ApiBundle\Listener\PopulationFilterRequestListener;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ReportController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Rapport",
     *     description="Récupère un rapport",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Get("/report/{rapId}", requirements={"rapId":"\d+"})
     *
     * @param $rapId
     *
     * @return DocapostJsonResponse
     */
    public function getReport($rapId)
    {
        $reportManager = $this->get('api.manager.report');
        $exportExcelManager = $this->get('api.manager.export_excel');
        // Lecture du rapport
        $report = $reportManager->getReportById($rapId);
        if (!$report instanceof RapRapport || !$reportManager->isCorrectUser($report)) {
            $this->addResponseMessage(RapRapport::ERR_RAP_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        if ($reportManager->isSearchReportType($report)) {
            // Exportation sous Excel si c'est un rapport de recherche
            if (!is_file($report->getRapFichier())) {
                $this->addResponseMessage(RapRapport::ERR_RAP_DOES_NOT_EXIST);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
            }
            $exportExcelManager->read($report->getRapFichier());
            return $exportExcelManager->export($report->getRapLibelleFic());
        } else {
            // Exportation CSV
            $exportExcelManager->create();
            $exportExcelManager->fromSimpleArray(unserialize($report->getRapFichier()));
            return $exportExcelManager->export($report->getRapLibelleFic());
        }
    }

    /**
     * @ApiDoc(
     *     section="Rapport",
     *     description="Récupère le rapport de recherche",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/report/search")
     *
     * @return DocapostJsonResponse
     */
    public function getReportSearch()
    {
        $report = $this->get('api.manager.report')->retieveOneReportByUserAndType(
            EnumLabelTypeRapportType::RECHERCHE_TYPE
        );
        if ($report instanceof RapRapport) {
            return $this->export([
                'rapId' => $report->getRapId(),
                'rapLibelleFic' => $report->getRapLibelleFic()
            ]);
        }
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Rapport",
     *     description="Récupère la liste des rapports de masse",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/report/mass")
     *
     * @return DocapostJsonResponse
     */
    public function getReportMass()
    {
        return $this->export($this->get('api.manager.report')->retrieveMassImportList());
    }

    /**
     * @ApiDoc(
     *     section="Rapport",
     *     description="Exporte la liste des flux CEL",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/report/cel/export")
     *
     * @return DocapostJsonResponse
     */
    public function postReportCelExport()
    {
        $reportManager = $this->get('api.manager.report');
        $datas = $this->getContentParameters(
            true,
            true,
            [
                'dateArchivage' => [
                    'start' => null,
                    'end' => null
                ],
                'periodeArchivage' => [
                    'start' => null,
                    'end' => null
                ],
                'source' => ['^CEL|VIS|VISION$'],
                'codePac' => [null],
                'codeSourceOrigine' => [null],
                'typeRapport' => '^periode|type$'
            ]
        );
        // Vérifie si un message a été positionné lors du traitement des paramètres
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Désactivation des filtres sur population
        $manager = $this->getDoctrine()->getManager();
        /** @var EntityManagerInterface $manager */
        $filter = new PopulationFilterRequestListener($manager, $this->get('security.token_storage'));
        $filter->disable();
        if (!$this->getDoctrine()->getRepository('ApiBundle:RidRefId')->checkPacs($datas['codePac'])) {
            $this->addResponseMessage(RcsRefCodeSociete::ERR_PAC_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!$reportManager->checkCelSourceCodes($datas['codeSourceOrigine'])) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_CEL_SOURCE_CODES_INCORRECT
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        return $reportManager->getReportCel($datas);
    }
}

<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\UsrUsers;

class UsrUsersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testUsrUsersEntity()
    {
        $myObject = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $date1 = new\DateTime();
        $date1 = $date1->modify('-3H');
        $date2 = new \DateTime();
        $date2 = $date2->modify('+6H');

        // Test Setter
        $myObject->setUsrLogin('MyUsrLogin');
        $myObject->setUsrPass('MyUsrPass');
        $myObject->setUsrOldPasswordList('MyUsrOldPasswordList');
        $myObject->setUsrChangePass(true);
        $myObject->setUsrPassLastModif(10);
        $myObject->setUsrPassNeverExpires(false);
        $myObject->setUsrMustChangePass(true);
        $myObject->setUsrSessionTime(1800);
        $myObject->setUsrActif(true);
        $myObject->setUsrRaison('MyUsrRaison');
        $myObject->setUsrConfidentialite('MyUsrConfidentialite');
        $myObject->setUsrPrendConfGroupe(true);
        $myObject->setUsrProfils('MyUsrProfils');
        $myObject->setUsrType('MyUsrType');
        $myObject->setUsrStatistiques(true);
        $myObject->setUsrOrsid(true);
        $myObject->setUsrMail(true);
        $myObject->setUsrAdresseMail('MyUsrAdresseMail');
        $myObject->setUsrCommentaires('MyUsrCommentaires');
        $myObject->setUsrNom('MyUsrNom');
        $myObject->setUsrPrenom('MyUsrPrenom');
        $myObject->setUsrAdressePost('MyUsrAdressePost');
        $myObject->setUsrTel('MyUsrTel');
        $myObject->setUsrRsoc('MyUsrRsoc');
        $myObject->setUsrGroupe('MyUsrGroupe');
        $myObject->setUsrExportCsv(true);
        $myObject->setUsrExportXls(false);
        $myObject->setUsrExportDoc(false);
        $myObject->setUsrRechercheLlk(true);
        $myObject->setUsrTelechargements(false);
        $myObject->setUsrAdminTele(true);
        $myObject->setUsrTiersArchivage(false);
        $myObject->setUsrCreateur('MyUsrCreateur');
        $myObject->setUsrTentatives(5);
        $myObject->setUsrMailCciAuto(true);
        $myObject->setUsrAdresseMailCciAuto('MyUsrAdresseMailCciAuto');
        $myObject->setUsrNbPanier(15);
        $myObject->setUsrNbRequete(20);
        $myObject->setUsrTypeHabilitation('MyUsrTypeHabilitation');
        $myObject->setUsrLangue('fr_FR');
        $myObject->setUsrAdp(false);
        $myObject->setUsrBeginningdate($date1);
        $myObject->setUsrEndingdate($date2);
        $myObject->setUsrStatus('MyUsrStatus');
        $myObject->setUsrNumBoiteArchive('MyUsrNumBoiteArchive');
        $myObject->setUsrMailCycleDeVie('MyUsrMailCycleDeVie');
        $myObject->setUsrRightAnnotationDoc(7);
        $myObject->setUsrRightAnnotationDossier(7);
        $myObject->setUsrRightClasser(0);
        $myObject->setUsrRightCycleDeVie(0);
        $myObject->setUsrRightRechercheDoc(3);
        $myObject->setUsrRightRecycler(0);
        $myObject->setUsrRightUtilisateurs(3);
        $myObject->setUsrAccessExportCel(false);
        $myObject->setUsrAccessImportUnitaire(true);
        $myObject->setUsrCreatedAt($now);
        $myObject->setUsrUpdatedAt($later);

        // Test Getter
        $this->assertEquals('MyUsrLogin', $myObject->getUsrLogin());
        $this->assertEquals('MyUsrPass', $myObject->getUsrPass());
        $this->assertEquals('MyUsrOldPasswordList', $myObject->getUsrOldPasswordList());
        $this->assertEquals(true, $myObject->isUsrChangePass());
        $this->assertEquals(10, $myObject->getUsrPassLastModif());
        $this->assertEquals(false, $myObject->isUsrPassNeverExpires());
        $this->assertEquals(true, $myObject->isUsrMustChangePass());
        $this->assertEquals(1800, $myObject->getUsrSessionTime());
        $this->assertEquals(true, $myObject->isUsrActif());
        $this->assertEquals('MyUsrRaison', $myObject->getUsrRaison());
        $this->assertEquals('MyUsrConfidentialite', $myObject->getUsrConfidentialite());
        $this->assertEquals(true, $myObject->isUsrPrendConfGroupe());
        $this->assertEquals('MyUsrProfils', $myObject->getUsrProfils());
        $this->assertEquals('MyUsrType', $myObject->getUsrType());
        $this->assertEquals(true, $myObject->hasUsrStatistiques());
        $this->assertEquals(true, $myObject->isUsrOrsid());
        $this->assertEquals(true, $myObject->isUsrMail());
        $this->assertEquals('MyUsrAdresseMail', $myObject->getUsrAdresseMail());
        $this->assertEquals('MyUsrCommentaires', $myObject->getUsrCommentaires());
        $this->assertEquals('MyUsrNom', $myObject->getUsrNom());
        $this->assertEquals('MyUsrPrenom', $myObject->getUsrPrenom());
        $this->assertEquals('MyUsrAdressePost', $myObject->getUsrAdressePost());
        $this->assertEquals('MyUsrTel', $myObject->getUsrTel());
        $this->assertEquals('MyUsrRsoc', $myObject->getUsrRsoc());
        $this->assertEquals('MyUsrGroupe', $myObject->getUsrGroupe());
        $this->assertEquals(true, $myObject->hasUsrExportCsv());
        $this->assertEquals(false, $myObject->hasUsrExportXls());
        $this->assertEquals(false, $myObject->hasUsrExportDoc());
        $this->assertEquals(true, $myObject->hasUsrRechercheLlk());
        $this->assertEquals(false, $myObject->hasUsrTelechargements());
        $this->assertEquals(true, $myObject->hasUsrAdminTele());
        $this->assertEquals(false, $myObject->hasUsrTiersArchivage());
        $this->assertEquals('MyUsrCreateur', $myObject->getUsrCreateur());
        $this->assertEquals(5, $myObject->getUsrTentatives());
        $this->assertEquals(true, $myObject->hasUsrMailCciAuto());
        $this->assertEquals('MyUsrAdresseMailCciAuto', $myObject->getUsrAdresseMailCciAuto());
        $this->assertEquals(15, $myObject->getUsrNbPanier());
        $this->assertEquals(20, $myObject->getUsrNbRequete());
        $this->assertEquals('MyUsrTypeHabilitation', $myObject->getUsrTypeHabilitation());
        $this->assertEquals('fr_FR', $myObject->getUsrLangue());
        $this->assertEquals(false, $myObject->isUsrAdp());
        $this->assertEquals($date1->getTimestamp(), $myObject->getUsrBeginningdate()->getTimestamp());
        $this->assertEquals($date2->getTimestamp(), $myObject->getUsrEndingdate()->getTimestamp());
        $this->assertEquals('MyUsrStatus', $myObject->getUsrStatus());
        $this->assertEquals('MyUsrNumBoiteArchive', $myObject->getUsrNumBoiteArchive());
        $this->assertEquals('MyUsrMailCycleDeVie', $myObject->getUsrMailCycleDeVie());
        $this->assertEquals(7, $myObject->getUsrRightAnnotationDoc());
        $this->assertEquals(7, $myObject->getUsrRightAnnotationDossier());
        $this->assertEquals(0, $myObject->getUsrRightClasser());
        $this->assertEquals(0, $myObject->getUsrRightCycleDeVie());
        $this->assertEquals(3, $myObject->getUsrRightRechercheDoc());
        $this->assertEquals(0, $myObject->getUsrRightRecycler());
        $this->assertEquals(3, $myObject->getUsrRightUtilisateurs());
        $this->assertEquals(false, $myObject->hasUsrAccessExportCel());
        $this->assertEquals(true, $myObject->hasUsrAccessImportUnitaire());
        $this->assertEquals($now->getTimestamp(), $myObject->getUsrCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getUsrUpdatedAt()->getTimestamp());
    }
}

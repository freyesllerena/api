<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelUserType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsrUsersData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->setUser01($manager);
        $this->setUser02($manager);
        $this->setUser03($manager);
        $this->setUserAutomate($manager);

        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }

    private function setUser01(ObjectManager $manager)
    {
        $user01 = new UsrUsers();
        $user01->setUsrLogin('MyUsrLogin01');
        $user01->setUsrOldPasswordList('');
        $user01->setUsrChangePass(false);
        $user01->setUsrPassLastModif(0);
        $user01->setUsrPassNeverExpires(true);
        $user01->setUsrMustChangePass(false);
        $user01->setUsrSessionTime(1800);
        $user01->setUsrActif(true);
        $user01->setUsrRaison('');
        $user01->setUsrConfidentialite('');
        $user01->setUsrPrendConfGroupe(true);
        $user01->setUsrProfils('P1');
        $user01->setUsrType('USR');
        $user01->setUsrStatistiques(false);
        $user01->setUsrOrsid(false);
        $user01->setUsrMail(false);
        $user01->setUsrAdresseMail('');
        $user01->setUsrCommentaires('');
        $user01->setUsrNom('MyUsrNom01');
        $user01->setUsrPrenom('MyUsrPrenom01');
        $user01->setUsrAdressePost('');
        $user01->setUsrTel('');
        $user01->setUsrRsoc('');
        $user01->setUsrGroupe('PAPERLESS');
        $user01->setUsrExportCsv(false);
        $user01->setUsrExportXls(false);
        $user01->setUsrExportDoc(false);
        $user01->setUsrRechercheLlk(false);
        $user01->setUsrTelechargements(false);
        $user01->setUsrAdminTele(true);
        $user01->setUsrTiersArchivage(false);
        $user01->setUsrCreateur('orsid');
        $user01->setUsrTentatives(0);
        $user01->setUsrMailCciAuto(false);
        $user01->setUsrAdresseMailCciAuto('');
        $user01->setUsrNbPanier(10);
        $user01->setUsrNbRequete(10);
        $user01->setUsrTypeHabilitation('expert');
        $user01->setUsrLangue('fr_FR');
        $user01->setUsrAdp(false);
        $user01->setUsrBeginningdate(new \DateTime('-1 day'));
        $user01->setUsrEndingdate(new \DateTime('+1 day'));
        $user01->setUsrStatus('ACTIV');
        $user01->setUsrMailCycleDeVie('myuserlogin01@cycledevie.mail');
        $user01->setUsrRightAnnotationDoc(0);
        $user01->setUsrRightAnnotationDossier(0);
        $user01->setUsrRightClasser(0);
        $user01->setUsrRightCycleDeVie(0);
        $user01->setUsrRightRechercheDoc(1);
        $user01->setUsrRightRecycler(0);
        $user01->setUsrRightUtilisateurs(0);
        $user01->setUsrAccessExportCel(false);
        $user01->setUsrAccessImportUnitaire(false);
        $manager->persist($user01);

        $this->addReference('usr_users01', $user01);
    }

    private function setUser02(ObjectManager $manager)
    {
        $user02 = new UsrUsers();
        $user02->setUsrLogin('MyUsrLogin02');
        $user02->setUsrOldPasswordList('');
        $user02->setUsrChangePass(false);
        $user02->setUsrPassLastModif(0);
        $user02->setUsrPassNeverExpires(true);
        $user02->setUsrMustChangePass(false);
        $user02->setUsrSessionTime(1800);
        $user02->setUsrActif(true);
        $user02->setUsrRaison('');
        $user02->setUsrConfidentialite('');
        $user02->setUsrPrendConfGroupe(true);
        $user02->setUsrProfils('P1');
        $user02->setUsrType('USR');
        $user02->setUsrStatistiques(false);
        $user02->setUsrOrsid(false);
        $user02->setUsrMail(false);
        $user02->setUsrAdresseMail('myusrnom02@test.fr');
        $user02->setUsrCommentaires('');
        $user02->setUsrNom('MyUsrNom02');
        $user02->setUsrPrenom('MyUsrPrenom02');
        $user02->setUsrAdressePost('');
        $user02->setUsrTel('');
        $user02->setUsrRsoc('');
        $user02->setUsrGroupe('PAPERLESS');
        $user02->setUsrExportCsv(false);
        $user02->setUsrExportXls(false);
        $user02->setUsrExportDoc(false);
        $user02->setUsrRechercheLlk(false);
        $user02->setUsrTelechargements(false);
        $user02->setUsrAdminTele(true);
        $user02->setUsrTiersArchivage(false);
        $user02->setUsrCreateur('orsid');
        $user02->setUsrTentatives(0);
        $user02->setUsrMailCciAuto(false);
        $user02->setUsrAdresseMailCciAuto('');
        $user02->setUsrNbPanier(10);
        $user02->setUsrNbRequete(10);
        $user02->setUsrTypeHabilitation('chef de file expert');
        $user02->setUsrLangue('fr_FR');
        $user02->setUsrAdp(false);
        $user02->setUsrBeginningdate(new \DateTime('-1 day'));
        $user02->setUsrEndingdate(new \DateTime('+1 day'));
        $user02->setUsrStatus('ACTIV');
        $user02->setUsrMailCycleDeVie('myuserlogin02@cycledevie.mail');
        $user02->setUsrRightAnnotationDoc(7);
        $user02->setUsrRightAnnotationDossier(7);
        $user02->setUsrRightClasser(7);
        $user02->setUsrRightCycleDeVie(3);
        $user02->setUsrRightRechercheDoc(7);
        $user02->setUsrRightRecycler(0);
        $user02->setUsrRightUtilisateurs(3);
        $user02->setUsrAccessExportCel(true);
        $user02->setUsrAccessImportUnitaire(true);
        $manager->persist($user02);

        $this->addReference('usr_users02', $user02);
    }

    private function setUser03(ObjectManager $manager)
    {
        $user03 = new UsrUsers();
        $user03->setUsrLogin('MyUsrLogin03');
        $user03->setUsrOldPasswordList('');
        $user03->setUsrChangePass(false);
        $user03->setUsrPassLastModif(0);
        $user03->setUsrPassNeverExpires(true);
        $user03->setUsrMustChangePass(false);
        $user03->setUsrSessionTime(1800);
        $user03->setUsrActif(true);
        $user03->setUsrRaison('');
        $user03->setUsrConfidentialite('');
        $user03->setUsrPrendConfGroupe(true);
        $user03->setUsrProfils('P1');
        $user03->setUsrType('USR');
        $user03->setUsrStatistiques(false);
        $user03->setUsrOrsid(false);
        $user03->setUsrMail(false);
        $user03->setUsrAdresseMail('');
        $user03->setUsrCommentaires('');
        $user03->setUsrNom('MyUsrNom03');
        $user03->setUsrPrenom('MyUsrPrenom03');
        $user03->setUsrAdressePost('');
        $user03->setUsrTel('');
        $user03->setUsrRsoc('');
        $user03->setUsrGroupe('PAPERLESS');
        $user03->setUsrExportCsv(false);
        $user03->setUsrExportXls(false);
        $user03->setUsrExportDoc(false);
        $user03->setUsrRechercheLlk(false);
        $user03->setUsrTelechargements(false);
        $user03->setUsrAdminTele(true);
        $user03->setUsrTiersArchivage(false);
        $user03->setUsrCreateur('orsid');
        $user03->setUsrTentatives(0);
        $user03->setUsrMailCciAuto(false);
        $user03->setUsrAdresseMailCciAuto('');
        $user03->setUsrNbPanier(10);
        $user03->setUsrNbRequete(10);
        $user03->setUsrTypeHabilitation('collaborateur');
        $user03->setUsrLangue('fr_FR');
        $user03->setUsrAdp(false);
        $user03->setUsrBeginningdate(new \DateTime('-1 day'));
        $user03->setUsrEndingdate(new \DateTime('+1 day'));
        $user03->setUsrStatus('ACTIV');
        $user03->setUsrMailCycleDeVie('myuserlogin03@cycledevie.mail');
        $user03->setUsrRightAnnotationDoc(1);
        $user03->setUsrRightAnnotationDossier(1);
        $user03->setUsrRightClasser(3);
        $user03->setUsrRightCycleDeVie(1);
        $user03->setUsrRightRechercheDoc(3);
        $user03->setUsrRightRecycler(0);
        $user03->setUsrRightUtilisateurs(0);
        $user03->setUsrAccessExportCel(false);
        $user03->setUsrAccessImportUnitaire(true);
        $manager->persist($user03);

        $this->addReference('usr_users03', $user03);
    }

    /**
     * Crée un utilisateur de type "Automate"
     *
     * @param ObjectManager $manager
     */
    private function setUserAutomate(ObjectManager $manager)
    {
        $automate = new UsrUsers();
        $automate->setUsrLogin('AutomateUser')
            ->setUsrPass(sha1('password'))
            ->setUsrOldPasswordList('')
            ->setUsrChangePass(false)
            ->setUsrPassLastModif(0)
            ->setUsrPassNeverExpires(true)
            ->setUsrMustChangePass(false)
            ->setUsrSessionTime(1800)
            ->setUsrActif(true)
            ->setUsrRaison('')
            ->setUsrConfidentialite('')
            ->setUsrPrendConfGroupe(true)
            ->setUsrProfils('P1')
            ->setUsrType(EnumLabelUserType::AUTO_USER)
            ->setUsrStatistiques(false)
            ->setUsrOrsid(false)
            ->setUsrMail(false)
            ->setUsrAdresseMail('')
            ->setUsrCommentaires('')
            ->setUsrNom('Automate')
            ->setUsrPrenom('Automate')
            ->setUsrAdressePost('')
            ->setUsrTel('')
            ->setUsrRsoc('')
            ->setUsrGroupe('PAPERLESS')
            ->setUsrExportCsv(false)
            ->setUsrExportXls(false)
            ->setUsrExportDoc(false)
            ->setUsrRechercheLlk(false)
            ->setUsrTelechargements(false)
            ->setUsrAdminTele(true)
            ->setUsrTiersArchivage(false)
            ->setUsrCreateur('orsid')
            ->setUsrTentatives(0)
            ->setUsrMailCciAuto(false)
            ->setUsrAdresseMailCciAuto('')
            ->setUsrNbPanier(10)
            ->setUsrNbRequete(10)
            ->setUsrTypeHabilitation('chef de file')
            ->setUsrLangue('fr_FR')
            ->setUsrAdp(false)
            ->setUsrBeginningdate(new \DateTime('2016-01-01'))
            ->setUsrEndingdate(new \DateTime('2016-12-31'))
            ->setUsrStatus('ACTIV')
            ->setUsrMailCycleDeVie('automate@docapost.fr')
            ->setUsrRightAnnotationDoc(1)
            ->setUsrRightAnnotationDossier(1)
            ->setUsrRightClasser(3)
            ->setUsrRightCycleDeVie(1)
            ->setUsrRightRechercheDoc(3)
            ->setUsrRightRecycler(0)
            ->setUsrRightUtilisateurs(0)
            ->setUsrAccessExportCel(false)
            ->setUsrAccessImportUnitaire(true);
        $manager->persist($automate);

        $this->addReference('usr_automate', $automate);
    }
}

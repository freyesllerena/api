<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelTypePerimeterType;
use Doctrine\ORM\QueryBuilder;

class ProProfilRepository extends BaseRepository
{
    /**
     * Renvoie les profils et éventuellement les filtres attachés à un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function findProfileFilterByUser(UsrUsers $user)
    {
        return $this->createQueryBuilder('pro')
            ->select([
                'pro.proId',
                'pde.pdeIdProfilDef',
                'pde.pdeType'
            ])
            ->join('ApiBundle:PusProfilUser', 'pus', 'WITH', 'pus.pusProfil = pro')
            ->leftJoin('ApiBundle:PdeProfilDef', 'pde', 'WITH', 'pde.pdeId = pro')
            ->where('pus.pusUser = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie les profils attachés à un utilisateur
     *
     * @param UsrUsers $user
     * @return mixed
     */
    public function findByUser(UsrUsers $user)
    {
        return $this->createQueryBuilder('pro', 'pro.proId')
            ->join('ApiBundle:PusProfilUser', 'pus', 'WITH', 'pus.pusProfil = pro')
            ->where('pus.pusUser = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie la liste des droits, filtres applicatifs et sur population de chaque utilisateur
     *
     * @return array
     */
    public function findUsersAllProfileFilters()
    {
        return $this->createQueryBuilder('pro')
            ->select([
                'usr.usrLogin',
                'usr.usrNom',
                'usr.usrPrenom',
                'usr.usrRightAnnotationDoc',
                'usr.usrRightAnnotationDossier',
                'usr.usrRightClasser',
                'usr.usrRightCycleDeVie',
                'usr.usrRightRechercheDoc',
                'usr.usrRightUtilisateurs',
                'usr.usrAccessExportCel',
                'usr.usrAccessImportUnitaire',
                'pro.proId',
                'pda.pdaLibelle',
                'pdh.pdhLibelle'
            ])
            ->join('ApiBundle:PusProfilUser', 'pus', 'WITH', 'pus.pusProfil = pro')
            ->join('pus.pusUser', 'usr')
            ->leftJoin('ApiBundle:PdeProfilDef', 'pde', 'WITH', 'pde.pdeId = pro')
            ->leftJoin(
                'ApiBundle:PdaProfilDefAppli',
                'pda',
                'WITH',
                'pda.pdaId = pde.pdeIdProfilDef AND pde.pdeType = :appli'
            )
            ->leftJoin(
                'ApiBundle:PdhProfilDefHabi',
                'pdh',
                'WITH',
                'pdh.pdhId = pde.pdeIdProfilDef AND pde.pdeType = :habi'
            )
            ->setParameters([
                'appli' => EnumLabelTypePerimeterType::APPLI_PERIMETER,
                'habi' => EnumLabelTypePerimeterType::HABI_PERIMETER
            ])
            ->getQuery()
            ->getArrayResult();
    }
}

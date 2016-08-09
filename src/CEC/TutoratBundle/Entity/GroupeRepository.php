<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 08/07/16
 * Time: 21:22
 */

namespace CEC\TutoratBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class GroupeRepository extends EntityRepository
{
    /**
     * Récupère le groupe de tutorat pour un lycée donné et un niveau donné
     *
     * @param Lycee, string
     * @return Groupe
     */
    public function findByNiveau($niveau)
    {
        $param_niveau = "";
        switch ($niveau) {
            case "Seconde":
                $param_niveau = "Secondes";
                break;
            case "Première":
                $param_niveau = "Premières";
                break;
            case "Terminale":
                $param_niveau = "Terminales";
                break;
            default:
        }
        $query = $this->createQueryBuilder('g')
            ->where('g.niveau = :niveau')
            ->setParameter('niveau', $param_niveau)
            ->getQuery();
        return $query->getResult();

    }

}
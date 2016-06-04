<?php

namespace CEC\CalendrierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Form\Type\SeanceType;
use CEC\ActiviteBundle\Form\Type\CompteRenduType;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Eleve;

use \DateTime;

class CalendrierController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CECCalendrierBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Affiche le planning de toutes les séances de tutorat.
     */
    public function afficherAction()
    {
        return $this->render('CECCalendrierBundle::planning.html.twig', array(
            'filtre'=>"all"));
    }

public function afficher_avec_filtreAction($filtre)
    {
        return $this->render('CECCalendrierBundle::planning.html.twig', array(
            'filtre'=>$filtre)
        );
    }
}

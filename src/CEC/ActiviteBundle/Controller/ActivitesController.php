<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ActivitesController extends Controller
{
    /**
     * Affiche une activité, sa description, la dernière version de son document,
     * ses informations (durée, type, tags) ainsi que les dernières notes attribuées.
     *
     * @param $activite : id de l'activité
     *
     * @Route("/activites/{activite}")
     * @Template()
     */
    public function voirAction($activite)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
        
        
        return array(
            'activite' => $activite,
        );
    }
}

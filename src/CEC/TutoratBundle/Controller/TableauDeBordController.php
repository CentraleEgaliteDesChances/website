<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CEC\TutoratBundle\Entity\Seance;


class TableauDeBordController extends Controller
{
    /**
     * Page d'index pour l'affichage du tableau de bord.
     * Cette page peut rediriger vers d'autres pages si nécessaire.
     */
    public function indexAction()
    {
        $membre = $this->getUser();
        
        // Si on est pas tuteur, invitation à compléter son profil
        if (!$membre->getGroupe())
        {
            $this->get('session')->getFlashBag()->add('success', 'Bonjour ' . $membre->getPrenom() . ' et bienvenue sur le site interne de CEC ! Tu n\'a pas encore complété ton profil et le groupe de tutorat auquel tu appartiens. Fais-le dès maintenant, en cliquant sur "Modifier mon profil", en bas à gauche de cette page.');
            return $this->redirect($this->generateUrl('membre'));
        }
        
        // Si on est tuteur, on vérifie qu'une prochaine séance est disponible
        $seances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($membre->getGroupe());
        if ($seances)
        {
            // On redirige vers la page de la prochaine séance
            usort($seances, function(Seance $a, Seance $b) {
                return $a->getDate() > $b->getDate();
            });
            $seance = $seances[0];
            return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
        } else {
            // On redirige vers le planning des séances du groupe
            $this->get('session')->getFlashBag()->add('info', 'Aucune séance de tutorat n\'est prévue ! Pour en créer une, utilisez le bouton ci-dessous.');
            return $this->render('CECMainBundle::base.html.twig');    // TODO: A remplacer par le planning des séances du groupe
        }
    }
    
}

<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DocumentsController extends Controller
{    
    /**
     * Suppression d'un document.
     * Supprime définitivement un document (et donc une version) de la base de donnée, ainsi que
     * les fichiers original et PDF associés sur le serveur.
     *
     * @Route("/documents/{document}/suppression")
     * @Template()
     */
    public function supprimerAction($document)
    {
        $document = $this->getDoctrine()->getRepository('CECActiviteBundle:Document')->find($document);
        if (!$document) throw $this->createNotFoundException("Impossible de trouver le document !");
        
        // Pour la redirection, on garde en mémoire l'activité associé
        $activite = $document->getActivite();
      
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($document);
        $entityManager->flush();
        
        $this->get('session')->getFlashBag()
            ->add('success', 'La version ainsi que les fichiers associés ont bien été supprimés.');
        return $this->redirect($this->generateUrl('cec_activite_activites_editer', array('activite' => $activite->getId())));
        
        return array();
    }
    
}

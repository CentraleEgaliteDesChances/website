<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use CEC\ActiviteBundle\Form\Type\DocumentType;
use CEC\ActiviteBundle\Entity\Document;

class DocumentsController extends Controller
{    
    /**
     * Suppression d'un document.
     * Supprime définitivement un document (et donc une version) de la base de donnée, ainsi que
     * les fichiers original et PDF associés sur le serveur.
     *
     * @param integer $activite : id de l'activité à laquelle appartient le document à supprimer
     * @param integer $document : id du document à supprimer
     *
     * @Template()
     */
    public function supprimerAction($activite, $document)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
    
        $document = $this->getDoctrine()->getRepository('CECActiviteBundle:Document')->findOneBy(array(
            'id' => $document,
            'activite' => $activite,
        ));
        if (!$document) throw $this->createNotFoundException("Impossible de trouver le document !");
        
        // S'il s'agit de la seule version de l'activité, on ne la supprime pas
        if (count($activite->getVersions()) == 1) {
            $this->get('session')->setFlash('error', 'Vous ne pouvez supprimer l\'unique version d\'une activité !');
            return $this->redirect($this->generateUrl('cec_activite_activites_editer', array('activite' => $activite->getId())));
        }
      
        $entityManager = $this->getDoctrine()->getEntityManager();
        $activite->removeVersion($document);
        $entityManager->remove($document);
        $entityManager->flush();
        
        $this->get('session')->getFlashBag()
            ->add('success', 'La version ainsi que les fichiers associés ont bien été supprimés.');
        return $this->redirect($this->generateUrl('cec_activite_activites_editer', array('activite' => $activite->getId())));
        
        return array();
    }
    
    /**
     * Ajout d'un nouveau document.
     * Permet d'ajouter un nouveau document (sous le forme d'une nouvelle version) à une activité.
     *
     * @param integer $activite : id de l'activité à laquelle on ajoute un nouveau document
     *
     * @Method("POST")
     */
    public function creerAction($activite)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
        
        // Ajoute l'auteur de la version et l'activité
        $document = new Document();
        $document->setAuteur($this->getUser());
        $document->setActivite($activite);
        
        $documentForm = $this->createForm(new DocumentType(), $document);
      
        // Hydrate le document avec les informations du formulaires
        $request = $this->getRequest();
        $documentForm->bindRequest($request);
        
        if ($documentForm->isValid())
        {
            $entityManager = $this->getDoctrine()->getEntityManager();
            $entityManager->persist($document);
            $entityManager->flush();
            
            $this->get('session')->getFlashBag()
                ->add('success', 'Le document a bien été téléchargé sur le serveur et la version ajoutéé à l\'activité.');
            return $this->redirect($this->generateUrl('cec_activite_activites_editer', array('activite' => $activite->getId())));
        }
                
        // Récupère la liste des erreurs dans des messages flash
        $flashBag = $this->get('session')->getFlashBag();
        $erreurs = $this->get('validator')->validate($document);
        foreach ($erreurs as $erreur) {
            $flashBag->add('error', $erreur->getMessage());
        }
        
        return $this->redirect($this->generateUrl('cec_activite_activites_editer', array('activite' => $activite->getId())));
    }
}

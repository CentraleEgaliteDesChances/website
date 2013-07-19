<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\ActiviteBundle\Form\Type\ActiviteType;
use CEC\ActiviteBundle\Form\Type\DocumentType;
use CEC\ActiviteBundle\Form\Type\NouvelleActiviteType;
use CEC\ActiviteBundle\Entity\Activite;
use CEC\ActiviteBundle\Entity\Document;
use CEC\ActiviteBundle\Entity\NouvelleActivite;

class ActivitesController extends Controller
{
    /**
     * TestAction.
     *
     * @Route("/test")
     * @Template()
     */
    public function testAction()
    {
        $maintenant = new \DateTime();
        
        $pdfFixture = "data-fixture.pdf";
        $wordFixture = "data-fixture.doc";
    
        $acti1 = new Activite();
        $acti1->setTitre("Activité 1")
              ->setDescription("Cette activité consiste en un data fixture permettant de tester le site.")
              ->setDuree("Entre 45 et 90 minutes")
              ->setType("Activité Culturelle")
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant);
              
        $acti1v1 = new Document();
        $acti1v1->setNomFichierPDF($pdfFixture)
                ->setNomFichierOriginal($wordFixture)
                ->setDescription('Téléchargement de la première version.')
                ->setDateCreation($maintenant)
                ->setDateModification($maintenant)
                ->setAuteur($this->getUser())
                ->setActivite($acti1);
        $acti1->addVersion($acti1v1);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($acti1);
        $em->persist($acti1v1);
        $em->flush();
        
        $em->remove($acti1);
        $em->flush();
        
        return array();
    }
    
    /**
     * Consultation d'une activité et de ses informations.
     * Affiche les données d'une activité (titre, description, type, durée, tags), un aperçu
     * de sa dernière version, ses compte-rendus et la compilation des notes (moyennes, nbr d'utilisations,
     * corrections depuis le dernier compte-rendu).
     * Propose un lien pour télécharger les documents, sélectionner (si disponible) l'activité pour
     * la prochaine séance, et modifier les données de l'activité.
     *
     * @param $activite : id de l'activité
     *
     * @Route("/activites/{activite}", requirements = {"activite" = "\d+"})
     * @Template()
     */
    public function voirAction($activite)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
        
        // On récupère les notes moyennes de cette activité
        $doctrine = $this->getDoctrine();
        $noteMoyenne['globale'] = $doctrine->getRepository('CECActiviteBundle:CompteRendu')
                                           ->getNoteMoyenneGlobalePourActivite($activite);
        $noteMoyenne['contenu'] = $doctrine->getRepository('CECActiviteBundle:CompteRendu')
                                           ->getNoteMoyenneContenuPourActivite($activite);
        $noteMoyenne['interactivite'] = $doctrine->getRepository('CECActiviteBundle:CompteRendu')
                                                 ->getNoteMoyenneInteractivitePourActivite($activite);
        $noteMoyenne['atteinteObjectifs'] = $doctrine->getRepository('CECActiviteBundle:CompteRendu')
                                                     ->getNoteMoyenneAtteinteObjectifsPourActivite($activite);
                                                     
        // On détermine si une nouvelle version est disponible (dernier document postérieur au dernier compte-rendu)
        $nouvelleVersion = false;
        $dernierCompteRendu = $doctrine->getRepository('CECActiviteBundle:CompteRendu')
                                       ->getDernierPourActivite($activite);
        if (!$dernierCompteRendu) {
            $nouvelleVersion = true;
        } else {
            $document = $activite->getDocument();
            if ($document) {
                $nouvelleVersion = ( $document->getDateCreation() > $dernierCompteRendu->getDateModification() );
            }
        }

        return array(
            'activite' => $activite,
            'note_moyenne' => $noteMoyenne,
            'nouvelle_version' => $nouvelleVersion,
        );
    }
    
    /**
     * Édition d'une activité et de ses informations.
     * Permet à l'utilisateur de :
     *     - modifier les données d'une activité (titre, type d'activité, description, durée estimée, tags associés) ;
     *     - gérer les versions (affichage, téléchargement et suppression des versions existantes) ;
     *     - téléchargement d'une nouvelle version ;
     *     - propose un bouton pour supprimer l'activité.
     *
     * @Route("/activites/{activite}/edition", requirements = {"activite" = "\d+"})
     * @Template()
     */
    public function editerAction($activite)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
        
        $activiteForm = $this->createForm(new ActiviteType(), $activite);
        $documentForm = $this->createForm(new DocumentType(), new Document());
        
        // On classe les versions par date de création
        $versionsTriees = $activite->getVersions()->toArray();
        usort($versionsTriees, function ($version1, $version2) {
            return $version1->getDateCreation() < $version2->getDateCreation();
        });
        
        $request = $this->getRequest();
        if ($request->isMethod('POST'))
        {
            $activiteForm->bindRequest($request);
            if ($activiteForm->isValid()) {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les modifications apportées à l\'activité ont bien été enregistrées.');
                return $this->redirect($this->generateUrl('cec_activite_activites_voir', array('activite' => $activite->getId())));
            }
        }
        
        return array(
            'activite' => $activite,
            'activite_form' => $activiteForm->createView(),
            'document_form' => $documentForm->createView(),
            'versions_triees' => $versionsTriees,
        );
    }
    
    /**
     * Création d'une activité.
     * Permet à l'utilisateur de remplir les informations d'une nouvelle séance et de télécharger une
     * première version de l'activité facilement. Un bouton créer permet d'ajouter le document
     * et l'activité dans la base de donnée, et télécharger les fichiers sur le serveur.
     *
     * @Route("/activites/creation")
     * @Template()
     */
    public function creerAction()
    {
        $nouvelleActivite = new NouvelleActivite();
        $activite = $nouvelleActivite->getActivite();
        $document = $nouvelleActivite->getDocument();
        
        $document->setDescription(Document::DocumentDescriptionPremiereVersion);
        $document->setActivite($activite);
        $document->setAuteur($this->getUser());
        $activite->addVersion($document);
        
        $form = $this->createForm(new NouvelleActiviteType(), $nouvelleActivite);
        
        $request = $this->getRequest();
        if ($request->isMethod('POST'))
        {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($activite);
                $entityManager->persist($document);
                $entityManager->flush();
                
                $this->get('session')
                    ->setFlash('success', 'L\'activité a bien été créé et la première version a été téléchargée sur le serveur.');
                return $this->redirect($this->generateUrl('cec_activite_activites_voir', array('activite' => $activite->getId())));
            }
        }
        
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * Suppression d'une activité.
     * Supprime définitivement l'activité de la base de donnée, et redirige vers la liste des activités.
     *
     * @Route("/activites/{activite}/suppression", requirements = {"activite" = "\d+"})
     * @Template()
     */
    public function supprimerAction($activite)
    {
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException("Impossible de trouver l'activité !");
        
        $activiteForm = $this->createForm(new ActiviteType(), $activite);
      
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($activite);
        $entityManager->flush();
        
        $this->get('session')->getFlashBag()
            ->add('success', 'L\'activité a bien été supprimée, ainsi que tous les documents et compte-rendus associés.');
        return $this->redirect($this->generateUrl('cec_activite_activites_voir', array('activite' => $activite->getId())));
        
        return array();
    }
    
}

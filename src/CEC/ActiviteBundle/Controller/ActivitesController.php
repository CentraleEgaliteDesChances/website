<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ActivitesController extends Controller
{
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
     * @Route("/activites/{activite}")
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
        
        // On classe les versions par date de création
        $versionsTriees = $activite->getVersions()->toArray();
        usort($versionsTriees, function ($version1, $version2) {
            return $version1->getDateCreation() < $version2->getDateCreation();
        });

        return array(
            'activite' => $activite,
            'note_moyenne' => $noteMoyenne,
            'nouvelle_version' => $nouvelleVersion,
            'versions_triees' => $versionsTriees,
        );
    }
}

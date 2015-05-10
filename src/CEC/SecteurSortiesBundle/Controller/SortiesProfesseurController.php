<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;

use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class SortiesProfesseurController extends Controller
{
    /**
     * Affiche les sorties à venir
     *
     * @Template()
     */
    public function voirAction()
    {
        $now = new \DateTime("now");

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findFollowingSorties($now);

        return array(
            'sorties' => $sorties
        );
    }

    /**
     * Affiche le menu des sorties
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        return $this->render('CECSecteurSortiesBundle:SortiesProfesseur:menu.html.twig', array(
            'request'         => $request,
        ));
    }

    /**
     * Affiche les sorties passées
     *
     * @Template()
     */
    public function voirAnciennesAction()
    {
        $now = new \DateTime("now");

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findPreviousSorties($now);

        return array(
            'sorties' => $sorties,
        );
    }
	
	/**
	* Affiche la liste des lycéens du lycée du prof référent
	*
	* @Template()
	*/
	public function lyceensAction(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$user = $this->getUser();
		$lycee = $user->getLycee();
		$lyceenstotaux = $sortie->getLyceens();
		$lyceens = array();
		
		foreach($lyceenstotaux as $lyceen)
		{
			if (equals($lyceen->getLycee(), $lycee))
			{
				$lyceens[] = $lyceen;
			}
		}
		
		return array('lyceens'=>$lyceens);
	}

    /**
    *
    * Affiche les participations aux sorties des élèves d'un lycée
    *
    * @param integer $lycee : id du lycée cherché
    *
    * @Template()
    */
    public function participationSortiesLyceeAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycee !');

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findAll();

        $anneesScolaires = array();
        $sortiesTotal = array();

        // On trie toutes les sorties par année scolaire
        foreach($sorties as $sortie)
        {
            $date = $sortie->getDateSortie();
            $annee = AnneeScolaire::withDate($date);

            if(!array_key_exists($annee->afficherAnnees(), $sortiesTotal))
            {
                $sortiesTotal[$annee->afficherAnnees()]= array($sortie);
            }
            else
            {
                $sortiesTotal[$annee->afficherAnnees()][] = $sortie;
            }

        }


        // On ne prend que les années scolaires ou un tutoré du lycée était présent
        foreach($lycee->getLyceens() as $lyceen)
        {
            $groupesLyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findByLyceen($lyceen);
            $anneesLyceens = array_map(function(GroupeEleves $ge){ return $ge->getAnneeScolaire();}, $groupesLyceen);

            foreach($anneesLyceens as $annee)
            {
                if(!in_array($annee, $anneesScolaires))
                    $anneesScolaires[] = $annee;
            }
        }
        
        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array('anneesScolaires' => $anneesScolaires, 'lycee'=>$lycee, 'sortiesTotal' => $sortiesTotal);

    }

}

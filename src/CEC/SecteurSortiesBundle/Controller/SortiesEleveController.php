<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Entity\SortieEleve;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;

use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class SortiesEleveController extends Controller
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
		
		$request= $this->getRequest();
			
		if ($request->isMethod("POST"))
        {          

            $em = $this->getDoctrine()->getEntityManager();  
					
			$eleve = $this->getUser();
			$mail = $eleve->getMail();
			$id = $request->get('id');
			$sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
			if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');

            $lyceensSortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBySortie($sortie);
            $lyceens = array_map(function(SortieEleve $s) {return $s->getLyceen();}, $lyceensSortie);
			
			// On bascule l'état
			if ($lyceens->contains($eleve))
			{
				
                // ON supprime l'élément de SortieEleve associé et on met à jour la place sur liste d'attente de tous les autres inscrits à la sortie
                $sortieEleve = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBy(array('sortie' => $sortie, 'lyceen' => $eleve));
                $em->remove($sortieEleve);
                $place = $sortieEleve->getListeAttente();

                foreach($lyceensSortie as $l)
                {
                    $rang = $l->getListeAttente();
                    if($rang > $place)
                    {
                        $l->setListeAttente($rang-1);
                    }
                }

				$this->get('cec.mailer')->sendLyceenDesinscrit($sortie);

				$request->getSession()->getFlashBag()->add('notice', 'Désinscription bien effectuée.');

			} else {
                // On crée une nouvelle instance de SortieEleve
				$sortieEleve = new SortieEleve();
                $sortieEleve->setSortie($sortie);
                $sortieEleve->setLyceen($eleve);

                // On calcule la place du lycéen sur liste d'attente
                $rang = 0;

                if($sortie->getPlaces() !=0 )
                {
                    $nbLyceens = count($this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBySortie($sortie));

                    $rang = ($nbLyceens - $sortie->getPlaces() > 0) ? $nbLyceens - $sortie->getPlaces() : 0;

                }

                $sortieEleve->setListeAttente($rang);
                if($rang > 0)
                    $sortieEleve->setPresence(false);


				$request->getSession()->getFlashBag()->add('notice', 'Inscription bien effectuée.');
			}
			
			
			$em->persist($sortie);
			$em->flush();
			
		}
		
        return array(
            'sorties' => $sorties,
        );
    }

    /**
     * Affiche le menu des sorties
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        return $this->render('CECSecteurSortiesBundle:SortiesEleve:menu.html.twig', array(
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
    * Méthode pour retourner la liste des sorties auxquelles a participé le tutoré
    *
    * @param integer $lyceen : id du lyceen
    *
    * @Template()
    */
    public function participationSortiesAction($lyceen)
    {
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findAll();

        $anneesScolaires = array();
        $sortiesTotal = array();

        // On trie toutes les sorties par Année Scolaire
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

        // On récupère les sorties effectuées par le lycéen
        $sortiesEffectuees = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findByLyceen($lyceen);
        $sortiesEffectuees = array_map(function(SortieEleve $s){ return $s->getSortie(); }, $sortiesEffectuees);

        // On ne prend que les années scolaires ou le tutoré était présent
        $groupesLyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findByLyceen($lyceen);
        $anneesScolaires = array_map(function(GroupeEleves $ge){ return $ge->getAnneeScolaire();}, $groupesLyceen);

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array(
                     'eleve' => $lyceen,
                     'sortiesEffectuees' => $sortiesEffectuees,
                     'sortiesTotal' => $sortiesTotal,
                     'anneesScolaires' => $anneesScolaires);

    }

}

<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;

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

}

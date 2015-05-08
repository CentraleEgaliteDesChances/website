<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;

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
					
			$eleve = $this->getUser();
			$mail = $eleve->getMail();
			$id = $request->get('id');
			$sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
			if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');
			
			// On bascule l'état
			if ($sortie->getLyceens()->contains($eleve))
			{
				$sortie->removeLyceen($eleve);
				$this->get('cec.mailer')->sendLyceenDesinscrit($sortie);

				$request->getSession()->getFlashBag()->add('notice', 'Désinscription bien effectuée.');
			} else {
				$lyceens = $sortie->addLyceen($eleve);

				$request->getSession()->getFlashBag()->add('notice', 'Inscription bien effectuée.');
			}
			
			$em = $this->getDoctrine()->getEntityManager();
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

}

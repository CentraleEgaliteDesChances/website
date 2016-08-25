<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 18/05/16
 * Time: 11:41
 */

namespace CEC\SecteurSortiesBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SortiesParentController extends Controller
{
    /**
     * Affiche les sorties à venir
     *
     * @Template()
     */
    public function voirAction()
    {
        $parent = $this->getUser();
        $now = new \DateTime("now");

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findFollowingSorties($now);

        return array(
            'sorties' => $sorties,
            'parent' => $parent
        );
    }

    /**
     * Affiche le menu des sorties
     *
     * @param Request $request: requête original
     * @return array
     */
    public function menuAction($request)
    {
        return $this->render('CECSecteurSortiesBundle:SortiesParent:menu.html.twig', array(
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
        $parent = $this->getUser();

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findPreviousSorties($now);

        return array(
            'sorties' => $sorties,
            'parent' => $parent
        );
    }

}
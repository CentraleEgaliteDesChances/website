<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Groupe;

use CEC\TutoratBundle\Form\Type\GroupeType;
use CEC\TutoratBundle\Form\Type\AjouterLyceenType;

class GroupesController extends Controller
{
    /**
     * Affiche la page d'un groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     */
    public function voirAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        // On rassemble les séances à venir
        $seances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($groupe);
        
        $lyceens = $groupe->getLyceens()->toArray();
        $tuteurs = $groupe->getTuteurs()->toArray();
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Groupes:voir.html.twig', array(
            'groupe'       => $groupe,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'seances'      => $seances,
        ));
    }
    
    /**
     * Permet l'édition d'un groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     */
    public function editerAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        // On rassemble les séances à venir
        $seances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($groupe);
        
        $lyceens = $groupe->getLyceens()->toArray();
        $tuteurs = $groupe->getTuteurs()->toArray();
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        $groupeForm = $this->createForm(new GroupeType(), $groupe);
        $ajouterLyceenForm = $this->createForm(new AjouterLyceenType());
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $request->request->has('groupe'))
        {
            $groupeForm->bindRequest($request);
            if ($groupeForm->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Groupes:editer.html.twig', array(
            'groupe'       => $groupe,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'seances'      => $seances,
            'groupe_form'  => $groupeForm->createView(),
            'ajouter_lyceen_form' => $ajouterLyceenForm->createView(),
        ));
    }
    
    /**
     * Retire un lycéen du groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $lyceen: id du lycéen
     */
    public function supprimerLyceenAction($groupe, $lyceen)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $lyceen->setGroupe(null);
        
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Ajoute un lycéen au groupe de tutorat
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $lyceen: id du lycéen — Variable POST
     */
    public function ajouterLyceenAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');

        // Get the lycéen
        $ajouterLyceenType = new AjouterLyceenType();    // Permet de trouver le nom du formulaire — Attention, ne doit pas
                                                  // se confondre avec le nom de la route, ajouter_lyceen !
        $data = $this->getRequest()->get($ajouterLyceenType->getName());
        if (in_array('lyceen', $data))
        {
            $lyceen = $data['lyceen'];
        } else {
            $this->get('session')->setFlash('error', 'Merci de spécifier un lycéen à ajouter');
            return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
        }
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $lyceen->setGroupe($groupe);
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
}

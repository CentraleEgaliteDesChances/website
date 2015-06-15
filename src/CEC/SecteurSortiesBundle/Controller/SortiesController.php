<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Entity\SortieEleve;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\AjouterLyceenType;

use CEC\TutoratBundle\Entity\Lycee;

class SortiesController extends Controller
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
	* Affiche les lycéens par onglet dans la liste des sorties
	*
	* @Template()
	*/
	public function lyceensAction(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$lyceensSortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBySortie($sortie);
		$lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();

        $lycees = array_filter($lycees, function(Lycee $l){ return !($l->getPivot());});
		
		
		return array(
			'lyceensSortie' => $lyceensSortie,
            'lycees' => $lycees
			);
	}

    /**
     * Affiche le menu des sorties
     *
     * @param Request $request: requête originale
     */
    public function menuAction($request)
    {
        return $this->render('CECSecteurSortiesBundle:Sorties:menu.html.twig', array(
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
     * Permet d'éditer une sortie ou de rédiger le CR
     *
     * @param integer $id Id de la sortie à modifier.
     * @param string $action Permet de différencier édition de la sortie et rédaction du CR
     * @Template()
     */
    public function editerAction($action, $id)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');

        switch($action):
            case 'editer':
                $form = $this->createForm(new SansCRSortieType(), $sortie);
                break;
            case 'cr':
                $form = $this->createForm(new CRSortieType(), $sortie);
                $ajouterLyceen = $this->createForm(new AjouterLyceenType($sortie));
                break;
            case 'editeraveccr':
                $form = $this->createForm(new SortieType(), $sortie);
                break;
            default:
                $this->redirect($this->generateUrl('sorties'));
        endswitch;

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                if ($action == 'cr')
                    $sortie->setOkCR(true);
                    $sortie->setnbLyceens(count($this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBy(array('sortie'=>$sortie, 'presence'=>true))));

                    $entityManager = $this->getDoctrine()->getEntityManager();
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                
                switch($action):
                    case 'editer':
                        $this->get('session')->getFlashBag()->add('success', "La sortie a bien été modifiée.");
						$this->get('cec.mailer')->sendSortieModifiee($sortie, $_SERVER['HTTP_HOST']);
                        return $this->redirect($this->generateUrl('sorties'));
                        break;
                    case 'cr':
                        $this->get('session')->getFlashBag()->add('success', "Le CR de la sortie a bien été rédigé.");
                        return $this->redirect($this->generateUrl('anciennes_sorties'));
                        break;
                    case 'editeraveccr':
                        $this->get('session')->getFlashBag()->add('success', "La sortie a bien été modifiée.");
						$this->get('cec.mailer')->sendSortieModifiee($sortie, $_SERVER['HTTP_HOST']);
                        return $this->redirect($this->generateUrl('anciennes_sorties'));
                        break;
                    default:
                        $this->redirect($this->generateUrl('sorties'));
                endswitch;

                
            }
        }

        if ($action == 'cr')
        {
            $template = 'CECSecteurSortiesBundle:Sorties:editerCR.html.twig';
            return $this->render($template, array(
                                                    'form' => $form->createView(),
                                                    'ajouter_lyceen_form' => $ajouterLyceen->createView(),
                                                    'sortie' => $sortie
        ));
        }
        else
        {
            $template = 'CECSecteurSortiesBundle:Sorties:editer.html.twig';

            return $this->render($template, array(
                'form' => $form->createView(),
                'sortie' => $sortie
            ));
        }
    }

    /**
    * Méthode permettant de basculer l'état de présence d'un tutoré en sortie
    * @var integer $sortie : id de la sortie concernée
    * @var integer $lyceen : id du lyceen concerné
    *
    */
    public function basculerLyceenAction($sortie, $lyceen)
    {
        $doctrine = $this->getDoctrine();

        $sortie = $doctrine->getRepository('CECSecteurSortiesBundle:Sortie')->find($sortie);
        if(!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie demandée.');

        $lyceen = $doctrine->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if(!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen demandé');

        $sortieLyceen = $doctrine->getRepository('CECSecteurSortiesBundle:SortieEleve')->findOneBy(array('sortie'=> $sortie, 'lyceen' => $lyceen));
        if(!$sortieLyceen) throw $this->createNotFoundException('Le lycéen n\'est pas inscrit à cette sortie');

        if($sortieLyceen->getPresence())
            $sortieLyceen->setPresence(false);
        else
            $sortieLyceen->setPresence(true);

        $doctrine->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('editer_sortie', array('action'=>'cr', 'id' => $sortie->getId())));
    }

    /**
    * Permet d'ajouter un lycéen à la sortie a posteriori s'il était sur liste d'attente et que l'absent ne s'est pas désinscrit pour libérer la place
    *
    * @param integer $sortie: id du sortie de tutorat
    * @param integer $lyceen: id du lycéen — Variable POST
    */
    public function ajouterLyceenAction($sortie)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($sortie);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver le sortie de tutorat !');

        // Récupère le lycéen
        $ajouterLyceenType = new AjouterLyceenType($sortie);    // Permet de trouver le nom du formulaire — Attention, ne doit pas
                                                  // se confondre avec le nom de la route, ajouter_lyceen !
        $data = $this->getRequest()->get($ajouterLyceenType->getName());
        if (array_key_exists('lyceen', $data))
        {
            $lyceen = $data['lyceen'];
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Merci de spécifier un lycéen à ajouter.');
            return $this->redirect($this->generateUrl('editer_sortie', array('action' => 'cr', 'id' => $sortie->getId())));
        }
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $sortieLyceen = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findOneBy(array('sortie'=>$sortie, 'lyceen'=>$lyceen));
        $sortieLyceen->setListeAttente(0);
        $sortieLyceen->setPresence(true);
        $em = $this->getDoctrine()->getEntityManager();

        $em->persist($sortieLyceen);
        $em->flush();
        
        return $this->redirect($this->generateUrl('editer_sortie', array('action' => 'cr', 'id' => $sortie->getId())));
    }


    /**
     * Permet de créer une sortie
     *
     * @Template()
     */
    public function creerAction()
    {
        $sortie = new Sortie();
        $form = $this->createForm(new SansCRSortieType(), $sortie);
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($sortie);

                $this->get('session')->getFlashBag()->add('success', "La sortie a bien été ajoutée.");
                $this->get('cec.mailer')->sendSortieCreee($sortie, $_SERVER['HTTP_HOST']);
                
                $entityManager->flush();

                return $this->redirect($this->generateUrl('sorties'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Supprime une sortie
     *
     * @param integer $id Id de la sortie à supprimer.
     * @Template()
     */
    public function supprimerSortieAction($id)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');


        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($sortie);
        $this->get('session')->getFlashBag()->add('success', 'La sortie a bien été définitivement supprimée.');
        $this->get('cec.mailer')->sendSortieSupprimee($sortie, $_SERVER['HTTP_HOST']);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('sorties'));
    }
	
    /**
    * Désinscrit un lycéen d'une sortie et met à jour la liste d'attente
    * @param : integer $sortie : id de la sortie concernée
    * @param : integer $lyceen : id du lycéen concerné
    *
    */
    public function desinscrireAction($sortie, $lyceen)
    {
        $doctrine = $this->getDoctrine();

        $sortie = $doctrine->getRepository('CECSecteurSortiesBundle:Sortie')->find($sortie);
        if(!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie concernée');

        $lyceen = $doctrine->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver ce lyceen');

        $lyceenSortie = $doctrine->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBy(array('sortie' => $sortie, 'lyceen' => $lyceen));
        if(!$lyceenSortie) throw $this->createNotFoundException('Ce lycéen n\'est pas inscrit à cette sortie');

        $em = $doctrine->getEntityManager();

        $em->remove($lyceenSortie);

        // On met à jour la liste d'attente

        $lyceensSortie = $doctrine->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBySortie($sortie);
        $place = $lyceenSortie->getListeAttente();

        foreach($lyceensSortie as $l)
        {
            $rang = $l->getListeAttente();
            if($rang > $place)
            {
                $l->setListeAttente($rang-1);
            }
        }
        
        return $this->redirect($this->generateUrl('sorties'));
    }
	
	/**
	* Crée l'excel des inscrits à une sortie
	*
	* @param integer $id Id de la sortie concernée
	* @Template()
	*/
	public function excelAction($id)
    {
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container, $id) {

            $em = $container->get('doctrine')->getManager();

            // Ecriture des infos de chaque lyceen de la sortie dans un fichier ouvert à la volée dans PHP pour pas surcharger le serveur
            
            $sortie = $em->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
            $handle = fopen('php://output', 'r+');
			$lyceensSortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:SortieEleve')->findBySortie($sortie);
			$tab = array('Rang', utf8_decode('Prénom'), 'Nom', 'Adresse mail', utf8_decode('Téléphone'), utf8_decode('Lycée'), utf8_decode('validé?'), 'Autorisation de sortie', 'Est venu');
			fputcsv($handle, $tab, ';');

            foreach($lyceensSortie as $lyceenSortie) 
            {
                if($lyceenSortie->getListeAttente() == 0)
                {

                    $lyceen = $lyceenSortie->getLyceen();
                
				    $tab = array('0', utf8_decode($lyceen->getPrenom()), utf8_decode($lyceen->getNom()), utf8_decode($lyceen->getMail()), $lyceen->getTelephone(), utf8_decode($lyceen->getLycee()),  '', '', '');
                    fputcsv($handle, $tab, ';');
                }                
            }

            if($sortie->getPlaces() > 0 and count($lyceensSortie) > $sortie->getPlaces())
            {
                $tab = array('=','=','=','=','=','=','=','=', '=');
                fputcsv($handle, $tab, ';');
                $tab = array('=', '=','=', '=', utf8_decode('Liste d\'attente'), '=', '=', '=','=');
                fputcsv($handle, $tab, ';');
                $tab = array('=','=','=','=','=','=','=','=', '=');
                fputcsv($handle, $tab, ';');
                $tab = array('Rang', utf8_decode('Prénom'), 'Nom', 'Adresse mail', utf8_decode('Téléphone'), utf8_decode('Lycée'), utf8_decode('validé?'), 'Autorisation de sortie', 'Est venu');
                fputcsv($handle, $tab, ';');

                foreach($lyceensSortie as $lyceenSortie)
                {
                    $lyceen = $lyceenSortie->getLyceen();

                    $tab = array($lyceenSortie->getListeAttente(), utf8_decode($lyceen->getPrenom()), utf8_decode($lyceen->getNom()), utf8_decode($lyceen->getMail()), $lyceen->getTelephone(), utf8_decode($lyceen->getLycee()),  '', '', '');
                    fputcsv($handle, $tab, ';');
                }

            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="export_inscrits_sortie'.$id.'.csv"');

        return $response;
    }

}

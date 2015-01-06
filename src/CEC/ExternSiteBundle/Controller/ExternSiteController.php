<?php

namespace CEC\ExternSiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ExternSiteController extends Controller
{

    public function indexAction($name)
    {
        return $this->render('CECExternSiteBundle:Pages:index.html.twig');
    }
	
	public function activitesAction()
	{
		return $this->render('CECExternSiteBundle:Pages:activites.html.twig');
	}

	public function actualitesAction()
	{
		return $this->render('CECExternSiteBundle:Pages:actualites.html.twig');
	}
	
	public function cecetvousAction()
	{
		return $this->render('CECExternSiteBundle:Pages:cecetvous.html.twig');
	}
	
	public function devenirpartenaireAction()
	{
		return $this->render('CECExternSiteBundle:Pages:devenir_partenaire.html.twig');
	}
	
	public function histoireAction()
	{
		return $this->render('CECExternSiteBundle:Pages:histoire.html.twig');
	}
	
	public function partenairesAction()
	{
		return $this->render('CECExternSiteBundle:Pages:partenaires.html.twig');
	}
	
	public function structureAction()
	{
		return $this->render('CECExternSiteBundle:Pages:structure.html.twig');
	}
}

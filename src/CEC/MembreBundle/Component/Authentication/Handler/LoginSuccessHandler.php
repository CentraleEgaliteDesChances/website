<?php
 
namespace CEC\MembreBundle\Component\Authentication\Handler;
 
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
 
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	protected $router;
	protected $security;
	public function __construct(Container $container, Router $router, SecurityContext $security)
	{
		$this->router = $router;
		$this->security = $security;
	}
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		if ($this->security->isGranted('ROLE_TUTEUR'))
		{
			$response = new RedirectResponse($this->router->generate('tableau_de_bord_tuteur'));	
		}
		elseif ($this->security->isGranted('ROLE_PROFESSEUR'))
		{
			$response = new RedirectResponse($this->router->generate('tableau_de_bord_professeur'));
		}
		elseif ($this->security->isGranted('ROLE_ELEVE'))
		{
			$response = new RedirectResponse($this->router->generate('tableau_de_bord_eleve'));
		}
		return $response;
	}
}
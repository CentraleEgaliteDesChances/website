<?php

namespace CEC\MembreBundle\Twig\Extension;
 
class LyceeExtension extends \Twig_Extension
{
    public function getFilters()
    {
       return array(
			new \Twig_SimpleFilter('lycee', array($this, 'doSomething')),
	   );
    }
 
    public function doSomething($value)
    {
        switch($value){
			case "cpb":
				return "Charles Péguy (Bobigny)";
				break;
			case "cpp":
				return "Charles Péguy (Paris)";
				break;
			case "jjmont":
				return "Jean-Jaurès (Montreuil)";
				break;
			case "jjchat":
				return "Jean-Jaurès (Châtenay)";
				break;
			case "matisse":
				return "Henri Matisse (Montreuil)";
				break;
			case "montesquieu":
				return "Montesquieu (Le Plessis-Robinson)";
				break;
			case "monod":
				return "Jacques Monod (Clamart)";
				break;
			case "mounier":
				return "Emmanuel Mounier (Châtenay)";
				break;
				}
    }
 
    public function getName()
    {
        return 'lycee_extension';
    }
 
}
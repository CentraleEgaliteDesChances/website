<?php

namespace CEC\MainBundle\Twig;

/**
 * Permet d'ajouter nos propres extensions à Twig.
 * 
 * Cette classe permet de définir des filtres et fonctions personnalisées
 * pouvant être utilisés dans tout le site. Pour cela, nous importons cette classe
 * comme service dans le MainBundle.
 *
 * @author Jean-Baptiste Bayle <jean-baptiste.bayle@student.ecp.fr>
 * @version 1.0
 */
class CECExtension extends \Twig_Extension
{
    public function getFilters() 
    {
        return array(
            // Capitalise les premières lettres de chaque mot
            'ucwords' => new \Twig_Filter_Function('ucwords'),
        );
    }
    
    public function getName() {
        return 'cec_extension';
    }
}
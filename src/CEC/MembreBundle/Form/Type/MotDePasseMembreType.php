<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MotDePasseMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
			->add('ancienMotDePasse', 'password', array( 
				'label' => 'Mot de passe actuel',
			))
			->add('motDePasse', 'repeated', array(
				'type' => 'password',
				'first_options' => array('label' => 'Nouveau mot de passe'),
				'second_options' => array('label' => 'Confirmez le nouveau mot de passe'),
			));
    }
    
    public function getName() {
        return 'MotDePasseMembre';
    }
}

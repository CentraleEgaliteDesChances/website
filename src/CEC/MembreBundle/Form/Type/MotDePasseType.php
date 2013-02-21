<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MotDePasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('motDePasse', 'repeated', array(
            'type' => 'password',
            'first_options' => array('label' => 'Mot de passe'),
            'second_options' => array('label' => 'Confirmez le mot de passe'),
        ));
    }
    
    public function getName() {
        return 'mot_de_passe';
    }
}

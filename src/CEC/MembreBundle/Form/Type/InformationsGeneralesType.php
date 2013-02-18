<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InformationsGeneralesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('prenom', null, array('label' => 'Prénom'));
        $builder->add('nom');
        $builder->add('email');
        $builder->add('telephone', null, array('label' => 'Numéro de téléphone'));
    }
    
    public function getName() {
        return 'informations_generales';
    }
}

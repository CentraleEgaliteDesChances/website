<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoixSecteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('secteurs', null, array(
            'expanded' => true,
            'label'    => 'Indiquez les secteurs dans lesquels vous vous impliquez :'
        ));
    }
    
    public function getName() {
        return 'choix_secteurs';
    }
}

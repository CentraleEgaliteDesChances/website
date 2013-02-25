<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NomCordeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nom', null, array(
            'label_render' => false,
            'attr' => array('placeholder' => 'Nom de la cordée', 'class' => 'input-nom'),
        ));
    }
    
    public function getName() {
        return 'nom_cordee';
    }
}

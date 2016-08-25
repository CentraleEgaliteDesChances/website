<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 14/08/16
 * Time: 19:08
 */

namespace CEC\MembreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SecteurMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('secteurs', 'entity', array(
            'label' => 'Choisissez des secteurs',
            'class' => 'CEC\MembreBundle\Entity\Secteur',
            'choice_label' => 'nom',
            'empty_value' => true,
            'multiple' => true,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }

    public function getName()
    {
        return 'SecteurMembre';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }
}
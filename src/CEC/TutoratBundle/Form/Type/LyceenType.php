<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LyceenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', null, array(
            'label' => 'Prénom',
            ))
            ->add('nom')
            ->add('groupe', null, array(
                'label' => 'Groupe de tutorat',
                'empty_value' => 'Aucun groupe de tutorat',
            ))
            ->add('telephone', null, array(
                'label' => 'Numéro de téléphone',
            ))
            ->add('email', null, array(
                'label' => 'Adresse Email',
            ))
            ->add('adresse', null, array(
                'label' => 'Adresse postale',
            ))
            ->add('codePostal', null, array(
                'label' => 'Code postal',
            ))
            ->add('ville')
            ->add('nomPere', null, array(
                'label' => 'Nom du père',
            ))
            ->add('nomMere', null, array(
                'label' => 'Nom de la mère',
            ))
            ->add('telephoneParent', null, array(
                'label' => 'Téléphone des parents',
            ))
            ->add('commentaires');
    }
    
    public function getName()
    {
        return 'Lyceen';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Lyceen',
        ));
    }
}
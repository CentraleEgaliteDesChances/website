<?php

namespace CEC\SecteurProjetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', null, array(
            'label' => 'Fichier PDF du dossier d\'inscription',
            'help_inline' => 'La taille du document ne peut excéder 10 Mo, et les formats acceptés sont les formats Adobe PDF (.pdf).',
            ))
			->add('projet', null, array(
			'label' => 'Projet concerné',
			'property' => 'nom'
			));
    }

    public function getName()
    {
        return 'Dossier';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurProjetsBundle\Entity\Dossier',
        ));
    }
}

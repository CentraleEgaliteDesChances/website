<?php

namespace CEC\SecteurProjetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CEC\SecteurProjetsBundle\Form\ImageType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('projet', null, array(
			'label' => 'Projet concerné',
			'property' => 'nom',
            'disabled' => $options['disabled'],
			))
			->add('annee', 'integer' , array(
			'label' => 'Année de l\'édition du projet',
            'disabled' => $options['disabled'],
			))
			->add('images', 'collection', array(
			'type' => new ImageType(),
			'allow_add' => true,
			'allow_delete' => true
			));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurProjetsBundle\Entity\Album',
            'disabled' => false
        ));
    }

    public function getName()
    {
        return 'AlbumType';
    }
}

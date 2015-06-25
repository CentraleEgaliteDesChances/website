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
            'label_attr' =>array('class' => 'col-md-3 control-label'),
			'property' => 'nom',
            'read_only' => $options['read_only'],
			))
			->add('annee', 'integer' , array(
			'label' => 'Année de l\'édition du projet',
            'label_attr' =>array('class' => 'col-md-3 control-label'),
            'read_only' => $options['read_only'],
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
            'read_only' => false
        ));
    }

    public function getName()
    {
        return 'AlbumType';
    }
}

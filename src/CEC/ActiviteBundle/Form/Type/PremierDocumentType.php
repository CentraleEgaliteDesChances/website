<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PremierDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fichierOriginal', null, array(
                'label' => false,
                'help_inline' => 'La taille du document ne peut excéder 10 Mo, et les formats acceptés sont les formats Microsoft Word et Microsoft PowerPoint (.doc, .docx, .ppt, .pptx).',
            ))
            ->add('fichierPDF', null, array(
                    'label' => false,
                    'help_inline' => 'La taille du document ne peut excéder 10 Mo, et les formats acceptés sont les formats Adobe PDF (.pdf).',
            ));
    }

    public function getName()
    {
        return 'PremierDocument';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\Document',
        ));
    }
}

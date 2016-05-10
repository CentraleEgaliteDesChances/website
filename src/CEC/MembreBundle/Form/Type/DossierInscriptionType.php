<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DossierInscriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('professionPere',null,array(
                'required' =>false
            ))
            ->add('professionMere',null,array(
                'required' =>false
            ))
            ->add('telephoneParent',null,array(
                'required' =>false
            ))
            ->add('mailParent')
            ->add('statutParents')
            ->add('nombrePersonnesACharge')
            ->add('nombreEnfants')
            ->add('enfant','collection', array(
                'required' =>false,
                'entry_type' => EnfantType::class,
                'allow_add' => true,
            ))
            ->add('boursier')
            ->add('bourses',null,array(
                'required' =>false
            ))
            ->add('raisonInscriptionCecParticipeAuProgramme','checkbox')
            ->add('nombreAnneeChezCec',null,array(
                'required' =>false
            ))
            ->add('raisonInscriptionCecEncourageParProche','checkbox')
            ->add('procheQuiAEncouragePourCec',null,array(
                'required' =>false
            ))
            ->add('raisonInscriptionCecCuriosite','checkbox')
            ->add('raisonInscriptionCecProgrammeEducatif','checkbox')
            ->add('raisonInscriptionCecSortiesProjets','checkbox')
            ->add('raisonInscriptionCecLycee','checkbox')
            ->add('matieresPreferees',null,array(
                'required' =>false
            ))
            ->add('matieresDetestees',null,array(
                'required' =>false
            ))
            ->add('ideeOrientationPostBac',null,array(
                'required' =>false
            ))
            ->add('ideeMetier',null,array(
                'required' =>false
            ))
            ->add('aisanceOral','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('aisanceSystemeScolaire','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('capaciteObtentionEtudesSouhaitees','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('informationEnseignementSuperieur','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('attachementActualites','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('interetScience','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => true
            ))
            ->add('activitesExtrascolaires')
            ->add('pratiqueMusee','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                ),
                'expanded' => true
            ))
            ->add('pratiqueTheatre','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                ),
                'expanded' => true
            ))
            ->add('pratiqueCinema','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                ),
                'expanded' => true
            ))
            ->add('pratiqueJournalTelevise','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  semaine' => 'Moins d\'1 fois /  semaine',
                    '1-2 fois / semaine' => '1-2 fois / semaine',
                    'Tous les jours' => 'Tous les jours'
                ),
                'expanded' => true
            ))
            ->add('pratiqueJournaux','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  semaine' => 'Moins d\'1 fois /  semaine',
                    '1-2 fois / semaine' => '1-2 fois / semaine',
                    'Tous les jours' => 'Tous les jours'
                ),
                'expanded' => true
            ))
            ->add('pratiqueLecture','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'De temps en temps' => 'De temps en temps',
                    'Souvent' => 'Souvent',
                    'Tous les jours' => 'Tous les jours'
                ),
                'expanded' => true
            ))
            ->add('projetsCecInterets','choice',array(
                'choices' => array(
                    'Good Morning London' => 'Good Morning London',
                    'Focus Europe' => 'Focus Europe',
                    '(Art)cessible' => '(Art)cessible',
                    'Stage Théâtre' => 'Stage Théâtre',
                    'Centrale Prépa' => 'Centrale Prépa'
                ),
                'expanded' => true,
                'multiple' => true
            ))
            ->add('langueVivante', 'collection', array(
                'entry_type' => \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('correspondantEtranger','textarea',array(
                'required' =>false
            ))
            ->add('interetEuropen','choice',array(
                'required' =>false,
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ),
                'expanded' => true
            ))
            ->add('voyagesRealises','textarea',array(
                'required' =>false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\DossierInscription'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cec_membrebundle_dossierinscription';
    }
}

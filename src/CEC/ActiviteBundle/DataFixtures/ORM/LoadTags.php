<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\Tag;

class LoadTags extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tagPremieres = new Tag("Premières");
        $tagTerminales = new Tag("Terminales");
        
        $tagExpressionOrale = new Tag("Expression orale");
        $tagEquationsDifferentielles = new Tag("Equations différentielles");
        $tagCultureGenerale = new Tag("Culture générale");
        
        $manager->persist($tagPremieres);
        $manager->persist($tagTerminales);
        $manager->persist($tagExpressionOrale);
        $manager->persist($tagEquationsDifferentielles);
        $manager->persist($tagCultureGenerale);
        $manager->flush();
        
        $this->addReference('tag_premieres', $tagPremieres);
        $this->addReference('tag_terminales', $tagTerminales);
        $this->addReference('tag_expression_orale', $tagExpressionOrale);
        $this->addReference('tag_equations_differentielles', $tagEquationsDifferentielles);
        $this->addReference('tag_culture_generale', $tagCultureGenerale);
    }
}

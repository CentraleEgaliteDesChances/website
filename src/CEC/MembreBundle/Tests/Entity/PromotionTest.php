<?php

namespace CEC\MembreBundle\Tests\Controller;

use CEC\MembreBundle\Entity\Promotion;

class PromotionTest extends \PHPUnit_Framework_TestCase
{
    public function provider() {
        return array(
            array('2014'),
            array(''),
            array(2014),
            array(-1)
        );
    }
    
    /**
     * @dataProvider provider
     */
    public function testCreation($annee) {
        $promotion = new Promotion();
        $promotion->setAnnee($annee);
        
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Promotion', $promotion);
        $this->assertEquals($annee, $promotion->getAnnee());
    }
}

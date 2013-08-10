<?php

namespace CEC\MainBundle\Tests\Utility;

use CEC\MainBundle\Utility\AnneeScolaire;

class AnneeScolaireTest extends \PHPUnit_Framework_TestCase
{
    protected $anneeScolaire;
    
    protected function setUp() {
        // Par défaut, l'année scolaire est l'année 2011-2012
        $this->anneeScolaire = new AnneeScolaire();
    }
    
    public function testCreate() {
        $this->assertInstanceOf("CEC\MainBundle\Utility\AnneeScolaire", new AnneeScolaire());
        $this->assertInstanceOf("CEC\MainBundle\Utility\AnneeScolaire", new AnneeScolaire(2040));
        $this->assertInstanceOf("CEC\MainBundle\Utility\AnneeScolaire", AnneeScolaire::withAnnees('2037-2038'));
        $this->assertInstanceOf("CEC\MainBundle\Utility\AnneeScolaire", AnneeScolaire::withDate(new \DateTime('2023-06-28')));
        $this->assertInstanceOf("CEC\MainBundle\Utility\AnneeScolaire", AnneeScolaire::withAnnees('2015/2016'));
    }
    
    public function testValidation() {
        try {
            AnneeScolaire::withDate(new \DateTime('2012-08-10'));
            AnneeScolaire::withDate(new \DateTime('3050-11-24'));
            AnneeScolaire::withAnnees('nimportequoi');
            AnneeScolaire::withAnnees('2011-2013');
            AnneeScolaire::withAnnees('200/201');
        } catch (\exception $e) {
            return;
        }
        $this->fail();
    }
    
    public function testGetters() {
        $this->assertEquals(2011, $this->anneeScolaire->getAnneeInferieure());
        $this->assertEquals(2012, $this->anneeScolaire->getAnneeSuperieure());
        $this->assertEquals(new \DateTime('2011-09-01'), $this->anneeScolaire->getDateRentree());
        $this->assertEquals(new \DateTime('2012-07-01'), $this->anneeScolaire->getDateSortie());
    }
    
    public function testSetters() {
        $as = new AnneeScolaire();
        
        $as->setAnneeInferieure(2020);
        $this->assertEquals(2020, $as->getAnneeInferieure());
        $this->assertEquals(2021, $as->getAnneeSuperieure());
        
        $as->setAnneeSuperieure(2020);
        $this->assertEquals(2019, $as->getAnneeInferieure());
        $this->assertEquals(2020, $as->getAnneeSuperieure());
    }
    
    public function testContientDate() {
        $as = $this->anneeScolaire;
        $this->assertTrue($as->contientDate(new \DateTime('2011-10-23')));
        $this->assertTrue($as->contientDate(new \DateTime('2012-03-10')));
        $this->assertFalse($as->contientDate(new \DateTime('2011-08-17')));
        $this->assertFalse($as->contientDate(new \DateTime('2012-07-02')));
    }
    
    public function testDescription() {
        $as = $this->anneeScolaire;
        $this->assertEquals('Année scolaire 2011-2012', $as);
        $this->assertEquals('2011-2012', $as->afficherAnnees());
    }
    
    public function testComparer() {
        $as2011 = AnneeScolaire::withAnnees('2011-2012');
        $as2012 = AnneeScolaire::withAnnees('2012-2013');
        $as20122 = AnneeScolaire::withAnnees('2012-2013');
        $as2013 = AnneeScolaire::withAnnees('2013-2014');
        $as2029 = AnneeScolaire::withAnnees('2029-2030');
        $this->assertEquals(-1, AnneeScolaire::comparer($as2011, $as2012));
        $this->assertEquals(-1, AnneeScolaire::comparer($as2012, $as2029));
        $this->assertEquals(0, AnneeScolaire::comparer($as2012, $as20122));
        $this->assertEquals(+1, AnneeScolaire::comparer($as2013, $as2011));
    }
}

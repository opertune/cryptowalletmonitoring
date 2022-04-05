<?php

namespace App\Tests;

use App\Entity\Price;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceRepositoryTest extends KernelTestCase
{
    private $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindOneByEmail()
    {
        $priceBySymbol = $this->em
            ->getRepository(Price::class)
            ->findBySymbol('btc');

        $this->assertSame(46075, $priceBySymbol->getPrice());
    }
}

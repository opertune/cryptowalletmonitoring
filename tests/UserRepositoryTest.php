<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
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
        $userByEmail = $this->em
            ->getRepository(User::class)
            ->findOneByEmail('dorothee.thierry@dumont.fr');

        $this->assertSame(1, $userByEmail->getId());
    }
}

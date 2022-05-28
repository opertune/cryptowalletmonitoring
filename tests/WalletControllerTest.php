<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WalletControllerTest extends WebTestCase
{
    /**
     * Redirect testing if user want access to wallet page while not logged in
     */
    public function testWalletAccessWhileNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/en/wallet');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/en/login');
    }
}

<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EncryptionKeyGeneratorCommand extends Command
{
    protected static $defaultName = 'app:encryption-key-generator';

    protected function configure()
    {
    }

    /**
     * Generate encryption key (32 bytes) and Initialization vector (26 bytes) for open_ssl encrypt and decrypt
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encryptionKey = $this->randomChar(32);
        $initializationVector = $this->randomChar(16);

        $output->writeln([
            "Encryption Key: <bg=green>$encryptionKey</>",
            "Initialization Vector: <bg=green>$initializationVector</>"
        ]);

        return Command::SUCCESS;
    }

    private function randomChar(int $bytes)
    {
        $string = '1234567890abcdefghijklmnopqrstuvxyz!@#$%^&*()_+:;.,<>/?\|{[]}';
        $randomString = '';
        for ($i = 0; $i < $bytes; $i++) {
            $randomString .= $string[rand(0, 60)];
        }

        return $randomString;
    }
}

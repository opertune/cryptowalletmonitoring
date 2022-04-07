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
        $string = '1234567890abcdefghijklmnopqrstuvxyz!@#$%^&*()_+:;.,<>/?\|{[]}'; //<- 60
        $randomString = '';
        for ($i = 0; $i < $bytes; $i++) {
            $randomString .= $string[rand(1, 60)];
        }

        return $randomString;
    }
}

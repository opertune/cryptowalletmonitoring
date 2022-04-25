<?php

namespace App\Command;

use App\Service\Utils;
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
     * Generate encryption key (32 bytes) and Initialization vector (16 bytes) for open_ssl encrypt and decrypt
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encryptionKey = Utils::randomChar(32);
        $initializationVector = Utils::randomChar(16);

        $output->writeln([
            "Encryption Key: <bg=green>$encryptionKey</>",
            "Initialization Vector: <bg=green>$initializationVector</>"
        ]);

        return Command::SUCCESS;
    }
}

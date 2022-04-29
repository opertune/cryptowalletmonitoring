<?php

namespace App\Command;

use App\Service\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class TimestampCommand extends Command
{
    protected static $defaultName = 'app:timestamp';
    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = HttpClient::create();
        $rep = $client->request('GET', 'https://api.binance.com/api/v3/time')->toArray();
        $binanceTimestamp = $rep['serverTime'];
        $serverTimestamp = round(microtime(true) * 1000);

        $output->writeln([
            "Binance timestamp: $binanceTimestamp",
            "Server timestamp: $serverTimestamp"
        ]);

        return Command::SUCCESS;
    }
}

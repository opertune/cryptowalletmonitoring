<?php

namespace App\Command;

use App\Entity\Price;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class GetAllCoinsPriceCommand extends Command
{
    private $entityManager;
    protected static $defaultName = 'app:getallprice';

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Get all cryptomonnaies prices from coingecko and add it in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $page = 1;
            $client = HttpClient::create();

            // Deleted all content in price table
            $this->entityManager->getConnection()->prepare('TRUNCATE TABLE price')->executeQuery();

            do {
                $response = $client->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=250&page=' . $page . '&sparkline=false&price_change_percentage=true');
                $response = $response->toArray();
                foreach ($response as $val) {
                    $price = new Price();
                    $price->setSymbol($val['symbol'])
                        ->setPrice($val['current_price']);
                    $this->entityManager->persist($price);
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
                $page++;
            } while ($response[249]['market_cap'] > 10000);

            // Add usd price manually because coingecko does not propose it
            $usd = new Price();
            $usd->setSymbol('usd')
                ->setPrice(1);
            $this->entityManager->persist($usd);
            $this->entityManager->flush();
            $this->entityManager->clear();


            $output->writeln([
                '======================================',
                '| Geted all coins price successfully |',
                '======================================',
            ]);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $output->writeln([
                "| Error: $error |",
            ]);
        }


        return Command::SUCCESS;
    }
}
